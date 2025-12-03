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
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
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
        
        .form-box {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            margin-top: 20px;
        }
        
        #map {
            height: 400px;
            border-radius: 10px;
            margin-top: 10px;
        }
        
        .required:after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('admin') ?>">
                <i class="fas fa-user-shield"></i> Admin Panel
            </a>
            <div class="ms-auto">
                <a href="<?= base_url('admin') ?>" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-box">
                    <h4 class="mb-4">
                        <i class="fas fa-<?= $action == 'create' ? 'plus' : 'edit' ?>"></i>
                        <?= $action == 'create' ? 'Tambah' : 'Edit' ?> Data Lokasi Bencana
                    </h4>

                    <!-- Validation Errors -->
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <strong>Terjadi Kesalahan:</strong>
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url($action == 'create' ? 'admin/store' : 'admin/update/' . $lokasi->id) ?>" 
                          method="POST">
                        <?= csrf_field() ?>

                        <div class="row">
                            <!-- Nama Lokasi -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Nama Lokasi</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="nama_lokasi" 
                                       value="<?= old('nama_lokasi', $lokasi->nama_lokasi ?? '') ?>"
                                       required>
                            </div>

                            <!-- Kecamatan -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Kecamatan</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="kecamatan" 
                                       value="<?= old('kecamatan', $lokasi->kecamatan ?? '') ?>"
                                       list="kecamatan-list"
                                       required>
                                <datalist id="kecamatan-list">
                                    <option value="Ajung">
                                    <option value="Ambulu">
                                    <option value="Arjasa">
                                    <option value="Balung">
                                    <option value="Bangsalsari">
                                    <option value="Gumukmas">
                                    <option value="Jelbuk">
                                    <option value="Jenggawah">
                                    <option value="Jombang">
                                    <option value="Kalisat">
                                    <option value="Kaliwates">
                                    <option value="Kencong">
                                    <option value="Ledokombo">
                                    <option value="Mayang">
                                    <option value="Mumbulsari">
                                    <option value="Pakusari">
                                    <option value="Panti">
                                    <option value="Patrang">
                                    <option value="Puger">
                                    <option value="Rambipuji">
                                    <option value="Semboro">
                                    <option value="Silo">
                                    <option value="Sukorambi">
                                    <option value="Sukowono">
                                    <option value="Sumberbaru">
                                    <option value="Sumberjambe">
                                    <option value="Sumbersari">
                                    <option value="Tanggul">
                                    <option value="Tempurejo">
                                    <option value="Umbulsari">
                                    <option value="Wuluhan">
                                </datalist>
                            </div>

                            <!-- Jenis Bencana -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Jenis Bencana</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="jenis_bencana" 
                                       value="<?= old('jenis_bencana', $lokasi->jenis_bencana ?? '') ?>"
                                       list="jenis-list"
                                       required>
                                <datalist id="jenis-list">
                                    <option value="Banjir">
                                    <option value="Banjir Rob">
                                    <option value="Tsunami">
                                    <option value="Tanah Longsor">
                                    <option value="Angin Kencang">
                                    <option value="Kekeringan">
                                    <option value="Tsunami / Banjir Rob">
                                    <option value="Banjir / Drainase">
                                    <option value="Banjir / Angin Kencang">
                                </datalist>
                            </div>

                            <!-- Longitude -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Longitude</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="longitude" 
                                       id="longitude"
                                       step="0.000001"
                                       value="<?= old('longitude', $lokasi->longitude ?? '') ?>"
                                       required>
                                <small class="text-muted">Format: 113.xxxxxx</small>
                            </div>

                            <!-- Latitude -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Latitude</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="latitude" 
                                       id="latitude"
                                       step="0.000001"
                                       value="<?= old('latitude', $lokasi->latitude ?? '') ?>"
                                       required>
                                <small class="text-muted">Format: -8.xxxxxx</small>
                            </div>

                            <!-- Tanggal Input -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label required">Tanggal Input</label>
                                <input type="date" 
                                       class="form-control" 
                                       name="tanggal_input" 
                                       value="<?= old('tanggal_input', $lokasi->tanggal_input ?? date('Y-m-d')) ?>"
                                       required>
                            </div>

                            <!-- Notes -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control" 
                                          name="notes" 
                                          rows="3"><?= old('notes', $lokasi->notes ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- Map untuk Pick Location -->
                        <div class="mb-3">
                            <label class="form-label">Pilih Lokasi di Peta (Klik untuk set koordinat)</label>
                            <div id="map"></div>
                            <small class="text-muted">Klik pada peta untuk mengisi koordinat otomatis</small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url('admin') ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Initialize map
        let currentLat = <?= old('latitude', $lokasi->latitude ?? -8.1706) ?>;
        let currentLng = <?= old('longitude', $lokasi->longitude ?? 113.7126) ?>;
        
        const map = L.map('map').setView([currentLat, currentLng], 11);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Marker
        let marker = L.marker([currentLat, currentLng], {
            draggable: true
        }).addTo(map);
        
        // Update form when marker moved
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat.toFixed(6);
            document.getElementById('longitude').value = position.lng.toFixed(6);
        });
        
        // Click on map to set marker
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
            document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
        });
        
        // Update marker when input changed
        document.getElementById('latitude').addEventListener('change', updateMarker);
        document.getElementById('longitude').addEventListener('change', updateMarker);
        
        function updateMarker() {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 13);
            }
        }
    </script>
</body>
</html>