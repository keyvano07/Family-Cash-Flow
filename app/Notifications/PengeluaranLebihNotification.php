<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable; // Menerapkan trait Queueable untuk mendukung antrian dalam pengiriman notifikasi
use Illuminate\Contracts\Queue\ShouldQueue; // Interface untuk mendukung notifikasi yang harus diantrekan
use Illuminate\Notifications\Messages\MailMessage; // Kelas untuk membangun pesan email
use Illuminate\Notifications\Notification; // Kelas dasar untuk notifikasi

class PengeluaranLebihNotification extends Notification
{
    use Queueable; // Menggunakan trait Queueable untuk mendukung fitur antrian

    private $selisih; // Properti untuk menyimpan nilai selisih antara pengeluaran dan pemasukan

    /**
     * Constructor untuk membuat instance notifikasi baru.
     *
     * @param float $selisih Nilai selisih antara pengeluaran dan pemasukan
     */
    public function __construct($selisih)
    {
        // Inisialisasi properti $selisih dengan nilai yang diberikan saat instance dibuat
        $this->selisih = $selisih;
    }

    /**
     * Mendefinisikan saluran pengiriman notifikasi.
     *
     * @param object $notifiable Objek yang menerima notifikasi
     * @return array<int, string> Daftar saluran yang akan digunakan untuk mengirim notifikasi
     */
    public function via(object $notifiable): array
    {
        // Menentukan bahwa notifikasi akan dikirim melalui email
        return ['mail'];
    }

    /**
     * Membuat representasi email dari notifikasi.
     *
     * @param object $notifiable Objek yang menerima notifikasi
     * @return MailMessage Objek MailMessage yang dikirimkan sebagai email
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Membuat dan mengembalikan pesan email
        return (new MailMessage)
            ->subject('Pengeluaran Melebihi Pemasukan') // Subjek email
            ->line('Pengeluaran Anda telah melebihi pemasukan.') // Pesan yang menjelaskan situasi
            ->line('Selisih: Rp' . number_format($this->selisih, 0, ',', '.')) // Menampilkan selisih dengan format angka yang rapi
            ->action('Cek Saldo', url('/dashboard')) // Tombol untuk memeriksa saldo, yang mengarahkan pengguna ke URL tertentu
            ->line('Harap periksa kembali pengeluaran Anda.'); // Pesan tambahan untuk pengguna
    }

    /**
     * Membuat representasi array dari notifikasi.
     *
     * @param object $notifiable Objek yang menerima notifikasi
     * @return array<string, mixed> Data yang disimpan sebagai notifikasi dalam bentuk array
     */
    public function toArray(object $notifiable): array
    {
        // Mengembalikan data notifikasi dalam bentuk array, cocok untuk penyimpanan di database
        return [
            'selisih' => $this->selisih, // Menyimpan nilai selisih sebagai bagian dari data notifikasi
            'message' => 'Pengeluaran Anda telah melebihi pemasukan.', // Pesan yang memberitahu pengguna tentang pengeluaran yang melebihi pemasukan
        ];
    }
}
