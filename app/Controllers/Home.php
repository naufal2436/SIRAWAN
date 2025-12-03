<?php

namespace App\Controllers;

use App\Models\LokasiModel;
use App\Entities\LokasiEntity;

class Home extends BaseController
{
    protected $lokasiModel;
    
    public function __construct()
    {
        $this->lokasiModel = new LokasiModel();
    }

    /**
     * Halaman Utama dengan Peta
     */
    public function index()
    {
        $data = [
            'title' => 'SIG Bencana Kabupaten Jember',
            'stats' => $this->lokasiModel->getDashboardStats(),
            'kecamatan_list' => $this->lokasiModel->getKecamatanList(),
            'jenis_list' => $this->lokasiModel->getJenisBencanaList()
        ];
        
        return view('home/index', $data);
    }

    /**
     * API: Get All Lokasi untuk Peta
     */
    public function getLokasi()
    {
        $lokasi = $this->lokasiModel->getAllLokasi();
        
        // Convert entities to array with custom methods
        $data = array_map(function(LokasiEntity $entity) {
            return $entity->toArray();
        }, $lokasi);
        
        return $this->response->setJSON($data);
    }

    /**
     * API: Get Detail Lokasi
     */
    public function getDetail($id)
    {
        $lokasi = $this->lokasiModel->getLokasiById($id);
        
        if (!$lokasi) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $lokasi->toArray()
        ]);
    }

    /**
     * API: Search Lokasi
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');
        
        if (empty($keyword)) {
            return $this->response->setJSON([]);
        }
        
        $results = $this->lokasiModel->searchLokasi($keyword);
        
        $data = array_map(function(LokasiEntity $entity) {
            return $entity->toArray();
        }, $results);
        
        return $this->response->setJSON($data);
    }

    /**
     * API: Filter by Kecamatan
     */
    public function filterKecamatan($kecamatan)
    {
        $results = $this->lokasiModel->filterByKecamatan($kecamatan);
        
        $data = array_map(function(LokasiEntity $entity) {
            return $entity->toArray();
        }, $results);
        
        return $this->response->setJSON($data);
    }

    /**
     * API: Find by Radius
     */
    public function findByRadius()
    {
        $lat = $this->request->getGet('lat');
        $lng = $this->request->getGet('lng');
        $radius = $this->request->getGet('radius') ?? 10;
        
        if (!$lat || !$lng) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Latitude dan longitude harus diisi'
            ]);
        }
        
        $results = $this->lokasiModel->findByRadius($lat, $lng, $radius);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * API: Get Statistics
     */
    public function getStatistik()
    {
        $data = [
            'dashboard' => $this->lokasiModel->getDashboardStats(),
            'per_jenis' => $this->lokasiModel->getStatistikJenis(),
            'per_kecamatan' => $this->lokasiModel->getStatistikKecamatan()
        ];
        
        return $this->response->setJSON($data);
    }
}