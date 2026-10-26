package main

import (
	"encoding/json"
	"fmt"
	"net/http"
)

type TransaksiRequest struct {
	SaldoSaatIni float64 `json:"saldo_saat_ini"`
	Jenis        string  `json:"jenis"`
	Jumlah       float64 `json:"jumlah"`
}

type TransaksiResponse struct {
	Status  bool    `json:"status"`
	Pesan   string  `json:"pesan"`
	Sisa    float64 `json:"sisa_saldo"`
}

func prosesTransaksi(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodPost {
		http.Error(w, "Method tidak diizinkan", http.StatusMethodNotAllowed)
		return
	}

	var req TransaksiRequest
	err := json.NewDecoder(r.Body).Decode(&req)
	if err != nil {
		http.Error(w, err.Error(), http.StatusBadRequest)
		return
	}

	sisaSaldo := req.SaldoSaatIni

	if req.Jenis == "masuk" {
		sisaSaldo += req.Jumlah
	} else if req.Jenis == "keluar" {
		if req.SaldoSaatIni < req.Jumlah {
			w.Header().Set("Content-Type", "application/json")
			json.NewEncoder(w).Encode(TransaksiResponse{
				Status:  false,
				Pesan:   "Saldo lu kurang, bro (Ditolak Golang API)!",
				Sisa:    req.SaldoSaatIni,
			})
			return
		}
		sisaSaldo -= req.Jumlah
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(TransaksiResponse{
		Status:  true,
		Pesan:   "Transaksi berhasil diproses via Golang!",
		Sisa:    sisaSaldo,
	})
}

func main() {
	http.HandleFunc("/api/transaksi", prosesTransaksi)

	fmt.Println("Golang Wallet API berjalan di http://localhost:8080...")
	if err := http.ListenAndServe(":8080", nil); err != nil {
		fmt.Println("Gagal menjalankan server Go:", err)
	}
}