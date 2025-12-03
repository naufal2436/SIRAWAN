<?php

namespace App\Controllers;

use App\Models\LokasiModel;
use App\Entities\LokasiEntity;

class Admin extends BaseController
{
    protected $lokasiModel;
    
    public function __construct()
    {
        $this->lokasiModel = new LokasiModel();
    }

    /**
     * Dashboard Admin
     */
    public function index()
    {
        $lokasi = $this->lokasiModel->getAllLokasi();
        
        $data = [
            'title' => 'Admin Dashboard - SIG Bencana Jember',
            'lokasi' => $lokasi,
            'stats' => $this->lokasiModel->getDashboardStats()
        ];
        
        return view('admin/index', $data);
    }

    /**
     * Form Tambah Data
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Data Lokasi Bencana',
            'action' => 'create',
            'lokasi' => null
        ];
        
        return view('admin/form', $data);
    }

    /**
     * Proses Simpan Data
     */
    public function store()
    {
        // Validasi
        if (!$this->validate($this->lokasiModel->getValidationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Data tidak valid!');
        }

        $data = [
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
            'kecamatan' => $this->request->getPost('kecamatan'),
            'longitude' => $this->request->getPost('longitude'),
            'latitude' => $this->request->getPost('latitude'),
            'jenis_bencana' => $this->request->getPost('jenis_bencana'),
            'tanggal_input' => $this->request->getPost('tanggal_input'),
            'notes' => $this->request->getPost('notes')
        ];

        if ($this->lokasiModel->insert($data)) {
            return redirect()->to('/admin')
                ->with('success', 'Data berhasil ditambahkan!');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data!');
        }
    }

    /**
     * Form Edit Data
     */
    public function edit($id)
    {
        $lokasi = $this->lokasiModel->find($id);
        
        if (!$lokasi) {
            return redirect()->to('/admin')
                ->with('error', 'Data tidak ditemukan!');
        }

        $data = [
            'title' => 'Edit Data Lokasi Bencana',
            'action' => 'edit',
            'lokasi' => $lokasi
        ];
        
        return view('admin/form', $data);
    }

    /**
     * Proses Update Data
     */
    public function update($id)
    {
        // Validasi
        if (!$this->validate($this->lokasiModel->getValidationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Data tidak valid!');
        }

        $data = [
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
            'kecamatan' => $this->request->getPost('kecamatan'),
            'longitude' => $this->request->getPost('longitude'),
            'latitude' => $this->request->getPost('latitude'),
            'jenis_bencana' => $this->request->getPost('jenis_bencana'),
            'tanggal_input' => $this->request->getPost('tanggal_input'),
            'notes' => $this->request->getPost('notes')
        ];

        if ($this->lokasiModel->update($id, $data)) {
            return redirect()->to('/admin')
                ->with('success', 'Data berhasil diupdate!');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate data!');
        }
    }

    /**
     * Hapus Data
     */
    public function delete($id)
    {
        if ($this->lokasiModel->delete($id)) {
            return redirect()->to('/admin')
                ->with('success', 'Data berhasil dihapus!');
        } else {
            return redirect()->to('/admin')
                ->with('error', 'Gagal menghapus data!');
        }
    }

    /**
     * Export Data ke CSV
     */
    public function export()
    {
        $lokasi = $this->lokasiModel->getAllLokasi();
        
        $filename = 'data_bencana_jember_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, [
            'ID', 'Nama Lokasi', 'Kecamatan', 'Longitude', 'Latitude',
            'Jenis Bencana', 'Tanggal Input', 'Notes'
        ]);
        
        // Data
        foreach ($lokasi as $row) {
            fputcsv($output, [
                $row->id,
                $row->nama_lokasi,
                $row->kecamatan,
                $row->longitude,
                $row->latitude,
                $row->jenis_bencana,
                $row->tanggal_input,
                $row->notes
            ]);
        }
        
        fclose($output);
        exit;
    }
}