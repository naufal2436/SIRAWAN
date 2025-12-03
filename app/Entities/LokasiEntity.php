<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

/**
 * LokasiEntity - Entity untuk Lokasi Bencana
 * Implementasi: Inheritance, Encapsulation, Polymorphism, Overriding
 */
class LokasiEntity extends Entity
{
    // Encapsulation: Protected properties
    protected $id;
    protected $nama_lokasi;
    protected $kecamatan;
    protected $longitude;
    protected $latitude;
    protected $jenis_bencana;
    protected $tanggal_input;
    protected $notes;
    protected $created_at;
    protected $updated_at;

    // Define which fields can be mass assigned
    protected $datamap = [];
    protected $dates = ['tanggal_input', 'created_at', 'updated_at'];
    protected $casts = [
        'id' => 'integer',
        'longitude' => 'float',
        'latitude' => 'float',
    ];

    // Validation errors
    protected $validationErrors = [];

    /**
     * Validate entity data
     * Polymorphism: Custom validation logic
     */
    public function validate(): bool
    {
        $this->validationErrors = [];

        // Validasi nama lokasi
        if (empty($this->nama_lokasi)) {
            $this->validationErrors['nama_lokasi'] = 'Nama lokasi harus diisi';
        } elseif (strlen($this->nama_lokasi) < 3) {
            $this->validationErrors['nama_lokasi'] = 'Nama lokasi minimal 3 karakter';
        }

        // Validasi kecamatan
        if (empty($this->kecamatan)) {
            $this->validationErrors['kecamatan'] = 'Kecamatan harus diisi';
        }

        // Validasi longitude
        if (empty($this->longitude)) {
            $this->validationErrors['longitude'] = 'Longitude harus diisi';
        } elseif (!$this->isValidLongitude($this->longitude)) {
            $this->validationErrors['longitude'] = 'Longitude tidak valid (harus antara -180 s/d 180)';
        }

        // Validasi latitude
        if (empty($this->latitude)) {
            $this->validationErrors['latitude'] = 'Latitude harus diisi';
        } elseif (!$this->isValidLatitude($this->latitude)) {
            $this->validationErrors['latitude'] = 'Latitude tidak valid (harus antara -90 s/d 90)';
        }

        // Validasi jenis bencana
        if (empty($this->jenis_bencana)) {
            $this->validationErrors['jenis_bencana'] = 'Jenis bencana harus diisi';
        }

        // Validasi tanggal
        if (empty($this->tanggal_input)) {
            $this->validationErrors['tanggal_input'] = 'Tanggal input harus diisi';
        }

        return empty($this->validationErrors);
    }

    /**
     * Get validation errors
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    /**
     * Check if latitude is valid
     */
    private function isValidLatitude($lat): bool
    {
        return is_numeric($lat) && $lat >= -90 && $lat <= 90;
    }

    /**
     * Check if longitude is valid
     */
    private function isValidLongitude($lng): bool
    {
        return is_numeric($lng) && $lng >= -180 && $lng <= 180;
    }

    /**
     * Get icon based on disaster type
     * Polymorphism: Different icons for different types
     */
    public function getIcon(): string
    {
        $jenis = strtolower($this->jenis_bencana);

        if (str_contains($jenis, 'banjir')) return '💧';
        if (str_contains($jenis, 'longsor')) return '⛰️';
        if (str_contains($jenis, 'tsunami')) return '🌊';
        if (str_contains($jenis, 'angin')) return '🌪️';
        if (str_contains($jenis, 'kekeringan')) return '☀️';
        if (str_contains($jenis, 'gempa')) return '🏚️';
        
        return '⚠️';
    }

    /**
     * Get marker color based on disaster type
     * Polymorphism: Different colors for different types
     */
    public function getMarkerColor(): string
    {
        $jenis = strtolower($this->jenis_bencana);

        if (str_contains($jenis, 'tsunami')) return '#dc3545'; // Red - High risk
        if (str_contains($jenis, 'longsor')) return '#fd7e14'; // Orange
        if (str_contains($jenis, 'banjir')) return '#0dcaf0'; // Cyan
        if (str_contains($jenis, 'angin')) return '#ffc107'; // Yellow
        if (str_contains($jenis, 'kekeringan')) return '#ff6b6b'; // Red
        
        return '#6c757d'; // Gray - default
    }

    /**
     * Get category based on disaster type
     * Polymorphism: Categorization logic
     */
    public function getKategori(): string
    {
        $jenis = strtolower($this->jenis_bencana);

        if (str_contains($jenis, 'tsunami') || str_contains($jenis, 'longsor')) {
            return 'Prioritas Tinggi';
        }
        if (str_contains($jenis, 'banjir')) {
            return 'Prioritas Sedang';
        }
        
        return 'Perlu Pemantauan';
    }

    /**
     * Format tanggal input untuk display
     */
    public function getTanggalFormatted(): string
    {
        if ($this->tanggal_input) {
            $date = date_create($this->tanggal_input);
            return date_format($date, 'd F Y');
        }
        return '-';
    }

    /**
     * Check if location is recent (within 3 months)
     */
    public function isRecent(): bool
    {
        if (!$this->tanggal_input) return false;
        
        $tanggal = strtotime($this->tanggal_input);
        $threeMonthsAgo = strtotime('-3 months');
        
        return $tanggal >= $threeMonthsAgo;
    }

    /**
     * Get distance from a point (in km)
     * Method Overloading simulation
     */
    public function getDistanceFrom(float $lat, float $lng): float
    {
        $earthRadius = 6371; // km

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($lat);
        $lonTo = deg2rad($lng);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Convert to array for API response
     * Overriding: Custom array structure
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nama_lokasi' => $this->nama_lokasi,
            'kecamatan' => $this->kecamatan,
            'longitude' => (float) $this->longitude,
            'latitude' => (float) $this->latitude,
            'jenis_bencana' => $this->jenis_bencana,
            'tanggal_input' => $this->tanggal_input,
            'tanggal_formatted' => $this->getTanggalFormatted(),
            'notes' => $this->notes,
            'icon' => $this->getIcon(),
            'color' => $this->getMarkerColor(),
            'kategori' => $this->getKategori(),
            'is_recent' => $this->isRecent(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    /**
     * Convert to JSON
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    // ==========================================
    // ENCAPSULATION: Getters and Setters
    // ==========================================

    public function setNamaLokasi(?string $nama_lokasi): self
    {
        $this->attributes['nama_lokasi'] = $nama_lokasi;
        return $this;
    }

    public function setKecamatan(?string $kecamatan): self
    {
        $this->attributes['kecamatan'] = $kecamatan;
        return $this;
    }

    public function setLongitude($longitude): self
    {
        $this->attributes['longitude'] = (float) $longitude;
        return $this;
    }

    public function setLatitude($latitude): self
    {
        $this->attributes['latitude'] = (float) $latitude;
        return $this;
    }

    public function setJenisBencana(?string $jenis_bencana): self
    {
        $this->attributes['jenis_bencana'] = $jenis_bencana;
        return $this;
    }

    public function setTanggalInput(?string $tanggal_input): self
    {
        $this->attributes['tanggal_input'] = $tanggal_input;
        return $this;
    }

    public function setNotes(?string $notes): self
    {
        $this->attributes['notes'] = $notes;
        return $this;
    }
}