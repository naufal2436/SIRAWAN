<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }
        
        .stats-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .stats-box h3 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .content-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .btn-action {
            margin: 0 2px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('admin') ?>">
                <i class="fas fa-user-shield"></i> Admin Panel - SIG Bencana Jember
            </a>
            <div class="ms-auto">
                <a href="<?= base_url() ?>" class="btn btn-light btn-sm">
                    <i class="fas fa-home"></i> Ke Halaman Utama
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="row">
            <div class="col-md-4">
                <div class="stats-box">
                    <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                    <h3><?= $stats['total_lokasi'] ?></h3>
                    <p class="mb-0">Total Lokasi</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-box">
                    <i class="fas fa-city fa-2x text-info"></i>
                    <h3><?= $stats['total_kecamatan'] ?></h3>
                    <p class="mb-0">Kecamatan</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-box">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                    <h3><?= $stats['total_jenis'] ?></h3>
                    <p class="mb-0">Jenis Bencana</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="content-box">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="fas fa-table"></i> Data Lokasi Rawan Bencana</h4>
                <div>
                    <a href="<?= base_url('admin/export') ?>" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export CSV
                    </a>
                    <a href="<?= base_url('admin/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover" id="tableData">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Lokasi</th>
                            <th>Kecamatan</th>
                            <th>Jenis Bencana</th>
                            <th>Koordinat</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($lokasi as $item): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $item->getIcon() ?> <?= esc($item->nama_lokasi) ?></td>
                                <td><?= esc($item->kecamatan) ?></td>
                                <td>
                                    <span class="badge" style="background: <?= $item->getMarkerColor() ?>;">
                                        <?= esc($item->jenis_bencana) ?>
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        <?= $item->latitude ?>, <?= $item->longitude ?>
                                    </small>
                                </td>
                                <td><?= $item->getTanggalFormatted() ?></td>
                                <td>
                                    <a href="<?= base_url('admin/edit/' . $item->id) ?>" 
                                       class="btn btn-sm btn-warning btn-action">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('admin/delete/' . $item->id) ?>" 
                                       class="btn btn-sm btn-danger btn-action"
                                       onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#tableData').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                pageLength: 25,
                order: [[5, 'desc']] // Sort by tanggal
            });
        });
    </script>
</body>
</html>