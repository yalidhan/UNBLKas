<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Departement;

class RealisasiAnggaranService
{
    public function generate(array $params): array
    {
        $sampai = $params['sampai'];
        $tahun  = Carbon::parse($sampai)->year;
        $departementParam = array_key_exists('departement', $params)
            ? (int) $params['departement']
            : null;
        /* ===============================
         |  DEPARTEMENT RESOLUTION
         =============================== */
        if ($departementParam === null) {

            $departementId = auth()->user()->departement_id;
            $departementSql = "b.departement_id = $departementId";
            $departement = Departement::find($departementId);

        } elseif ($departementParam === 0) {

            $departementSql = "b.departement_id NOT IN (1,18,19,20,21)";
            $departement = [
                'id'   => 0,
                'nama' => 'Universitas Borneo Lestari',
            ];

        } elseif ($departementParam === 1) {

            $departementSql = "b.departement_id IN (1,19,20,21)";
            $departement = [
                'id'   => 1,
                'nama' => 'Yayasan Borneo Lestari',
            ];

        } else {

            $departementId = $departementParam;
            $departementSql = "b.departement_id = $departementId";
            $departement = Departement::find($departementId);
        }
        /* ===============================
         |  TRANSACTION DEPARTEMENT FILTER
         =============================== */
        if ($departementParam === null) {

            $trxDepartementSql = "t.departement_id = $departementId";

        } elseif ($departementParam === 0) {

            $trxDepartementSql = "t.departement_id NOT IN (1,18,19,20,21)";

        } elseif ($departementParam === 1) {

            $trxDepartementSql = "t.departement_id IN (1,19,20,21)";

        } else {

            $trxDepartementSql = "t.departement_id = $departementId";
        }
// dd($departementParam, $departementSql, $trxDepartementSql);
        /* ===============================
         |  KELOMPOK FILTER
         =============================== */
        $kelompokFilter = empty($params['kelompok_anggaran'])
            ? "NOT IN ('','Transfer Antar Bank')"
            : "= '{$params['kelompok_anggaran']}'";

        /* ===============================
         |  KELOMPOK QUERY
         =============================== */
        $kelompok = DB::select("
            SELECT 
                a.kelompok,
                SUM(bd.nominal) AS pagu
            FROM budgets b
            JOIN budget_details bd ON bd.budget_id = b.id
            JOIN accounts a ON a.id = bd.account_id
            WHERE $departementSql
              AND b.tahun = ?
              AND a.kelompok $kelompokFilter
            GROUP BY a.kelompok
            ORDER BY a.kelompok
        ", [$tahun]);

        /* ===============================
         |  BUILD FINAL ROWS
         =============================== */
        $rows = [];

        foreach ($kelompok as $k) {

        $details = DB::select("
            SELECT 
                a.id AS account_id,
                a.nama,
                SUM(bd.nominal) AS pagu
            FROM budget_details bd
            JOIN accounts a ON a.id = bd.account_id
            JOIN budgets b ON b.id = bd.budget_id
            WHERE a.kelompok = ?
            AND b.tahun = ?
            AND $departementSql
            GROUP BY a.id, a.nama, a.no
            ORDER BY a.no
            ", [$k->kelompok, $tahun]);

            foreach ($details as $d) {

            // $realisasi = DB::selectOne("
            //     SELECT
            //         SUM(CASE WHEN d.dk = 2 THEN d.nominal ELSE 0 END) -
            //         SUM(CASE WHEN d.dk = 1 THEN d.nominal ELSE 0 END) AS total
            //     FROM transactions t
            //     JOIN transaction_details d ON d.transaction_id = t.id
            //     WHERE d.account_id = ?
            //     AND $trxDepartementSql
            //     AND t.tanggal BETWEEN ? AND ?
            // ", [$d->account_id, "$tahun-01-01", $sampai]);
                $realisasi = DB::selectOne("
                    SELECT
                        SUM(CASE WHEN td.dk = 2 THEN td.nominal ELSE 0 END) -
                        SUM(CASE WHEN td.dk = 1 THEN td.nominal ELSE 0 END) AS total
                    FROM transactions t
                    JOIN transaction_details td ON td.transaction_id = t.id
                    WHERE td.account_id = ?
                    AND $trxDepartementSql
                    AND t.tanggal BETWEEN ? AND ?
                ", [
                    $d->account_id,
                    "$tahun-01-01",
                    $sampai
                ]);

                $realisasi = (int) ($realisasi->total ?? 0);
                $sisa = $d->pagu - $realisasi;

                $rows[] = [
                    'kelompok'  => $k->kelompok,
                    'akun'      => $d->nama,
                    'pagu'      => (int) $d->pagu,
                    'realisasi' => $realisasi,
                    'persen'    => $d->pagu
                        ? round($realisasi / $d->pagu * 100, 2)
                        : 0,
                    'sisa'      => $sisa,
                ];
            }
        }

        return [
            'rows'       => $rows,
            'departement'=> $departement,
            'tahun'      => $tahun,
            'sampai'     => $sampai,
        ];
    }
}
