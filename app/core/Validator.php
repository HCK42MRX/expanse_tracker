<?php

class Validator
{
    /**
     * Menyimpan pesan error hasil validasi.
     * @var array
     */
    private $errors = [];

    /**
     * Menyimpan data yang sudah bersih (sanitized).
     * @var array
     */
    private $sanitized_data = [];

    /**
     * Fungsi utama untuk melakukan validasi dan sanitasi.
     *
     * @param array $data Data yang akan divalidasi (e.g., $_POST).
     * @param array $rules Aturan untuk setiap field.
     * @return bool True jika validasi berhasil, false jika gagal.
     */
    public function validate(array $data, array $rules): bool
    {
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;

            // 1. Cek aturan 'required'
            if (in_array('required', $rule) && (is_null($value) || trim($value) === '')) {
                $this->errors[$field] = "Field {$field} harus diisi.";
                continue; // Lanjut ke field berikutnya
            }

            // Jika field tidak wajib dan kosong, tidak perlu divalidasi lebih lanjut
            if (!in_array('required', $rule) && (is_null($value) || trim($value) === '')) {
                $this->sanitized_data[$field] = $value;
                continue;
            }

            // Bersihkan data awal
            $clean_value = trim($value);

            // 2. Proses validasi dan sanitasi berdasarkan aturan lain
            foreach ($rule as $constraint) {
                // Jika sudah ada error untuk field ini, hentikan validasi aturan selanjutnya
                if (isset($this->errors[$field])) {
                    break;
                }

                switch ($constraint) {
                    case 'numeric':
                        if (!is_numeric($clean_value)) {
                            $this->errors[$field] = "Field {$field} harus berupa angka.";
                        } else {
                            // Sanitasi hanya jika sudah pasti angka
                            $clean_value = filter_var($clean_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        }
                        break;

                    // --- PENAMBAHAN ATURAN BARU DI SINI ---
                    case 'not_zero':
                        // Pastikan nilainya numerik sebelum membandingkan
                        if (is_numeric($clean_value) && (float) $clean_value === 0.0) {
                            $this->errors[$field] = "Field {$field} tidak boleh bernilai nol.";
                        }
                        break;
                    // --- AKHIR PENAMBAHAN ---

                    case 'text':
                        $clean_value = htmlspecialchars($clean_value, ENT_QUOTES, 'UTF-8');
                        break;

                    case 'date':
                        $d = DateTime::createFromFormat('Y-m-d', $clean_value);
                        if (!$d || $d->format('Y-m-d') !== $clean_value) {
                            $this->errors[$field] = "Format tanggal untuk field {$field} harus YYYY-MM-DD.";
                        }
                        break;

                    default:
                        // Menangani aturan custom seperti 'in:...'
                        if (strpos($constraint, 'in:') === 0) {
                            $validValues = explode(',', substr($constraint, 3));
                            if (!in_array($clean_value, $validValues)) {
                                $this->errors[$field] = "Nilai untuk field {$field} tidak valid.";
                            }
                        }
                        break;
                }
            }

            // Simpan data yang sudah bersih jika tidak ada error
            if (!isset($this->errors[$field])) {
                $this->sanitized_data[$field] = $clean_value;
            }
        }

        return empty($this->errors);
    }

    /**
     * Mengambil pesan-pesan error.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Mengambil data yang sudah divalidasi dan dibersihkan.
     *
     * @return array
     */
    public function getSanitizedData(): array
    {
        return $this->sanitized_data;
    }
}