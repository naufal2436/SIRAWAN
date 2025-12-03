<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
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
            font-size: 1.4rem;
            color: white !important;
        }
        
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-radius: 15px;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-card h3 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0.5rem 0;
        }
        
        .map-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        
        #map {
            height: 600px;
            border-radius: 10px;
        }
        
        .sidebar {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin: 0.75rem 0;
        }
        
        .legend-color {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 12px;
            border: 3px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }
        
        footer {
            background: #2c3e50;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        @media (max-width: 768px) {
            #map {
                height: 400px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-map-marked-alt"></i> SIG Bencana Jember
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#peta">Peta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#data">Data</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin') ?>">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Hero -->
        <div class="hero text-center" id="beranda">
            <h1><i class="fas fa-shield-alt"></i> Sistem Informasi Geografis</h1>
            <p class="lead">Lokasi Rawan Bencana di Kabupaten Jember</p>
            <hr style="border-color: rgba(255,255,255,0.3); margin: 1.5rem auto; width: 50%;">
            <p>Spatial Database untuk Mitigasi dan Kesiapsiagaan Bencana</p>
        </div>

        <!-- Stats -->
        <div class="row">
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-map-marker-alt fa-2x" style="color: var(--primary);"></i>
                    <h3><?= $stats['total_lokasi'] ?></h3>
                    <p>Total Lokasi Rawan</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-city fa-2x" style="color: var(--secondary);"></i>
                    <h3><?= $stats['total_kecamatan'] ?></h3>
                    <p>Kecamatan</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-exclamation-triangle fa-2x" style="color: #dc3545;"></i>
                    <h3><?= $stats['total_jenis'] ?></h3>
                    <p>Jenis Bencana</p>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="row" id="peta">
            <div class="col-lg-9">
                <div class="map-container">
                    <h4><i class="fas fa-map"></i> Peta Lokasi Rawan Bencana</h4>
                    <div id="map"></div>
                </div>
            </div>
            
            <div class="col-lg-3">
                <!-- Search -->
                <div class="sidebar">
                    <h5><i class="fas fa-search"></i> Pencarian</h5>
                    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Cari lokasi...">
                    <div id="searchResults"></div>
                </div>
                
                <!-- Filter -->
                <div class="sidebar">
                    <h5><i class="fas fa-filter"></i> Filter</h5>
                    <select id="filterKecamatan" class="form-select mb-2">
                        <option value="">Semua Kecamatan</option>
                        <?php foreach ($kecamatan_list as $kec): ?>
                            <option value="<?= $kec ?>"><?= $kec ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="filterJenis" class="form-select">
                        <option value="">Semua Jenis Bencana</option>
                        <?php foreach ($jenis_list as $jenis): ?>
                            <option value="<?= $jenis ?>"><?= $jenis ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Legend -->
                <div class="sidebar">
                    <h5><i class="fas fa-list"></i> Legenda</h5>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #dc3545;"></div>
                        <span>Tsunami / Prioritas Tinggi</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #fd7e14;"></div>
                        <span>Tanah Longsor</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #0dcaf0;"></div>
                        <span>Banjir</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #ffc107;"></div>
                        <span>Angin Kencang</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="row" id="data">
            <div class="col-12">
                <div class="table-container">
                    <h4><i class="fas fa-table"></i> Data Lokasi Rawan Bencana</h4>
                    <div class="table-responsive">
                        <table class="table table-hover" id="tableLokasi">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lokasi</th>
                                    <th>Kecamatan</th>
                                    <th>Jenis Bencana</th>
                                    <th>Koordinat</th>
                                    <th>Tanggal Input</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <!-- Data akan dimuat via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p class="mb-2">&copy; 2025 SIG Bencana Kabupaten Jember</p>
            <p class="mb-0">Kelompok 9 - Spatial Basis Data - Universitas Jember</p>
        </div>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        const baseUrl = '<?= base_url() ?>';
        let allMarkers = [];
        let map;
        
        // Initialize Map
        map = L.map('map').setView([-8.1706, 113.7126], 11);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Load data
        function loadData() {
            fetch(baseUrl + '/home/getLokasi')
                .then(response => response.json())
                .then(data => {
                    displayMarkers(data);
                    displayTable(data);
                })
                .catch(error => console.error('Error:', error));
        }
        
        // Display markers on map
        function displayMarkers(data) {
            // Clear existing markers
            allMarkers.forEach(marker => map.removeLayer(marker));
            allMarkers = [];
            
            data.forEach(lokasi => {
                const customIcon = L.divIcon({
                    className: 'custom-marker',
                    html: `<div style="background: ${lokasi.color}; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 3px 8px rgba(0,0,0,0.3); font-size: 18px;">${lokasi.icon}</div>`,
                    iconSize: [35, 35]
                });
                
                const marker = L.marker([lokasi.latitude, lokasi.longitude], {icon: customIcon}).addTo(map);
                
                marker.bindPopup(`
                    <div style="min-width: 250px;">
                        <h6 style="color: ${lokasi.color}; font-weight: bold;">
                            ${lokasi.icon} ${lokasi.nama_lokasi}
                        </h6>
                        <p><strong>Kecamatan:</strong> ${lokasi.kecamatan}</p>
                        <p><strong>Jenis:</strong> ${lokasi.jenis_bencana}</p>
                        <p><strong>Kategori:</strong> <span style="color: ${lokasi.color};">${lokasi.kategori}</span></p>
                        <p><strong>Tanggal:</strong> ${lokasi.tanggal_formatted}</p>
                        ${lokasi.notes ? '<p><strong>Catatan:</strong> ' + lokasi.notes + '</p>' : ''}
                        <hr>
                        <small>Lat: ${lokasi.latitude}, Lng: ${lokasi.longitude}</small>
                    </div>
                `);
                
                allMarkers.push(marker);
            });
        }
        
        // Display data in table
        function displayTable(data) {
            let html = '';
            data.forEach((lokasi, index) => {
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${lokasi.icon} ${lokasi.nama_lokasi}</td>
                        <td>${lokasi.kecamatan}</td>
                        <td><span class="badge" style="background: ${lokasi.color};">${lokasi.jenis_bencana}</span></td>
                        <td>${lokasi.latitude}, ${lokasi.longitude}</td>
                        <td>${lokasi.tanggal_formatted}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="zoomTo(${lokasi.latitude}, ${lokasi.longitude})">
                                <i class="fas fa-search-location"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            $('#tableBody').html(html);
            
            if ($.fn.DataTable.isDataTable('#tableLokasi')) {
                $('#tableLokasi').DataTable().destroy();
            }
            
            $('#tableLokasi').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                pageLength: 10
            });
        }
        
        // Zoom to location
        function zoomTo(lat, lng) {
            map.setView([lat, lng], 15);
            $('html, body').animate({
                scrollTop: $('#peta').offset().top - 100
            }, 500);
        }
        
        // Search
        $('#searchInput').on('input', function() {
            const keyword = $(this).val();
            
            if (keyword.length < 3) {
                $('#searchResults').html('');
                return;
            }
            
            fetch(baseUrl + '/home/search?q=' + encodeURIComponent(keyword))
                .then(response => response.json())
                .then(data => {
                    let html = '<div class="list-group mt-2">';
                    
                    if (data.length === 0) {
                        html += '<div class="list-group-item">Tidak ada hasil</div>';
                    } else {
                        data.forEach(item => {
                            html += `
                                <a href="#" class="list-group-item list-group-item-action" 
                                   onclick="zoomTo(${item.latitude}, ${item.longitude}); return false;">
                                    <strong>${item.icon} ${item.nama_lokasi}</strong><br>
                                    <small>${item.jenis_bencana} - ${item.kecamatan}</small>
                                </a>
                            `;
                        });
                    }
                    
                    html += '</div>';
                    $('#searchResults').html(html);
                });
        });
        
        // Filter by Kecamatan
        $('#filterKecamatan').on('change', function() {
            const kecamatan = $(this).val();
            
            if (!kecamatan) {
                loadData();
                return;
            }
            
            fetch(baseUrl + '/home/filterKecamatan/' + encodeURIComponent(kecamatan))
                .then(response => response.json())
                .then(data => {
                    displayMarkers(data);
                    displayTable(data);
                });
        });
        
        // Load initial data
        $(document).ready(function() {
            loadData();
        });
    </script>
</body>
</html>