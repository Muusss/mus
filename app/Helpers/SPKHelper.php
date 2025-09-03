<?php

namespace App\Helpers;

class SPKHelper
{
    /**
     * Format skor untuk tampilan
     */
    public static function formatSkor($skor)
    {
        return number_format($skor, 3, ',', '.');
    }
    
    /**
     * Get status berdasarkan nilai
     */
    public static function getStatus($nilai)
    {
        if ($nilai >= 3.5) {
            return ['label' => 'Sangat Baik', 'color' => 'green'];
        } elseif ($nilai >= 2.5) {
            return ['label' => 'Baik', 'color' => 'blue'];
        } elseif ($nilai >= 1.5) {
            return ['label' => 'Cukup', 'color' => 'yellow'];
        } else {
            return ['label' => 'Perlu Bimbingan', 'color' => 'red'];
        }
    }
    
    /**
     * Get icon untuk kriteria
     */
    public static function getKriteriaIcon($nama)
    {
        $icons = [
            'Nilai Raport Umum' => 'fas fa-graduation-cap',
            'Nilai Raport Diniyah' => 'fas fa-mosque',
            'Akhlak' => 'fas fa-heart',
            'Hafalan Al-Quran' => 'fas fa-quran',
            'Kehadiran' => 'fas fa-calendar-check',
            'Ekstrakurikuler' => 'fas fa-trophy'
        ];
        
        return $icons[$nama] ?? 'fas fa-star';
    }
    
    /**
     * Get warna untuk kriteria
     */
    public static function getKriteriaColor($index)
    {
        $colors = [
            'blue', 'green', 'purple', 'yellow', 'red', 'indigo'
        ];
        
        return $colors[$index % count($colors)];
    }
}