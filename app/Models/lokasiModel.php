<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\LokasiEntity;

/**
 * LokasiModel
 * Implementasi: Entity-based model dengan OOP
 */
class LokasiModel extends Model
{
    protected $table = 'lokasi_bencana';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = LokasiEntity::class; // Return as Entity
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'nama_lokasi',
        'kecamatan',
        'longitude',
        'latitude',
        'jenis_bencana',
        'tanggal_input',
        'notes'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'nama_lokasi' => 'required|min_length[3]|max_length[255]',
        'kecamatan' => 'required|max_length[255]',
        'longitude' => 'required|decimal',
        'latitude' => 'required|decimal',
        'jenis_bencana' => 'required|max_length[255]',
        'tanggal_input' => 'required|valid_date'
    ];

    protected $validationMessages = [
        'nama_lokasi' => [
            'required' => 'Nama lokasi harus diisi',
            'min_length' => 'Nama lokasi minimal 3 karakter'
        ],
        'kecamatan' => [
            'required' => 'Kecamatan harus diisi'
        ],
        'longitude' => [
            'required' => 'Longitude harus diisi',
            'decimal' => 'Longitude harus berupa angka'
        ],
        'latitude' => [
            'required' => 'Latitude harus diisi',
            'decimal' => 'Latitude harus berupa angka'
        ],
        'jenis_bencana' => [
            'required' => 'Jenis bencana harus diisi'
        ],
        'tanggal_input' => [
            'required' => 'Tanggal input harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ]
    ];

    /**
     * Get all locations with entity conversion
     */
    public function getAllLokasi(): array
    {
        return $this->orderBy('tanggal_input', 'DESC')->findAll();
    }

    /**
     * Get lokasi by ID
     */
    public function getLokasiById($id): ?LokasiEntity
    {
        return $this->find($id);
    }

    /**
     * Insert with Entity validation
     * Polymorphism: Accepts Entity or Array
     */
    public function insertLokasi($data): bool
    {
        // If it's an Entity
        if ($data instanceof LokasiEntity) {
            if (!$data->validate()) {
                $this->errors = $data->getValidationErrors();
                return false;
            }
            $data = $data->toRawArray();
        }

        return $this->insert($data) !== false;
    }

    /**
     * Update with Entity validation
     */
    public function updateLokasi($id, $data): bool
    {
        // If it's an Entity
        if ($data instanceof LokasiEntity) {
            if (!$data->validate()) {
                $this->errors = $data->getValidationErrors();
                return false;
            }
            $data = $data->toRawArray();
            unset($data['id']); // Remove ID from update data
        }

        return $this->update($id, $data);
    }

    /**
     * Search lokasi
     */
    public function searchLokasi(string $keyword): array
    {
        return $this->like('nama_lokasi', $keyword)
                    ->orLike('kecamatan', $keyword)
                    ->orLike('jenis_bencana', $keyword)
                    ->findAll();
    }

    /**
     * Filter by kecamatan
     */
    public function filterByKecamatan(string $kecamatan): array
    {
        return $this->where('kecamatan', $kecamatan)
                    ->orderBy('tanggal_input', 'DESC')
                    ->findAll();
    }

    /**
     * Filter by jenis bencana
     */
    public function filterByJenisBencana(string $jenis): array
    {
        return $this->like('jenis_bencana', $jenis)
                    ->orderBy('tanggal_input', 'DESC')
                    ->findAll();
    }

    /**
     * Get locations within radius (km)
     */
    public function findByRadius(float $lat, float $lng, float $radius = 10): array
    {
        $all = $this->findAll();
        $results = [];

        foreach ($all as $lokasi) {
            $distance = $lokasi->getDistanceFrom($lat, $lng);
            if ($distance <= $radius) {
                $results[] = [
                    'lokasi' => $lokasi,
                    'jarak_km' => $distance
                ];
            }
        }

        // Sort by distance
        usort($results, function($a, $b) {
            return $a['jarak_km'] <=> $b['jarak_km'];
        });

        return $results;
    }

    /**
     * Get statistics by jenis bencana
     */
    public function getStatistikJenis(): array
    {
        return $this->select('jenis_bencana, COUNT(*) as jumlah')
                    ->groupBy('jenis_bencana')
                    ->orderBy('jumlah', 'DESC')
                    ->findAll();
    }

    /**
     * Get statistics by kecamatan
     */
    public function getStatistikKecamatan(): array
    {
        return $this->select('kecamatan, COUNT(*) as jumlah')
                    ->groupBy('kecamatan')
                    ->orderBy('jumlah', 'DESC')
                    ->findAll();
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        $stats = [
            'total_lokasi' => $this->countAll(),
            'total_kecamatan' => $this->select('COUNT(DISTINCT kecamatan) as total')
                                     ->first()['total'] ?? 0,
            'total_jenis' => $this->select('COUNT(DISTINCT jenis_bencana) as total')
                                  ->first()['total'] ?? 0,
        ];

        // Get top jenis bencana
        $topJenis = $this->select('jenis_bencana, COUNT(*) as jumlah')
                         ->groupBy('jenis_bencana')
                         ->orderBy('jumlah', 'DESC')
                         ->findAll();

        $stats['per_jenis'] = [];
        foreach ($topJenis as $row) {
            $stats['per_jenis'][$row['jenis_bencana']] = $row['jumlah'];
        }

        return $stats;
    }

    /**
     * Get unique kecamatan list
     */
    public function getKecamatanList(): array
    {
        return $this->select('DISTINCT kecamatan')
                    ->orderBy('kecamatan', 'ASC')
                    ->findColumn('kecamatan');
    }

    /**
     * Get unique jenis bencana list
     */
    public function getJenisBencanaList(): array
    {
        return $this->select('DISTINCT jenis_bencana')
                    ->orderBy('jenis_bencana', 'ASC')
                    ->findColumn('jenis_bencana');
    }

    /**
     * Get recent locations (last 30 days)
     */
    public function getRecentLokasi(int $days = 30): array
    {
        $date = date('Y-m-d', strtotime("-{$days} days"));
        return $this->where('tanggal_input >=', $date)
                    ->orderBy('tanggal_input', 'DESC')
                    ->findAll();
    }
}