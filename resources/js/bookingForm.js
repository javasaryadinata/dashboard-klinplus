export default function BookingForm() {
    return {
        debugBypassValidation: false,
        isLoading: false,
        errorMessage: "",
        selectedLayanan: [],
        showDropdown: false,
        showBookingDetail: false,
        showSuccessModal: false,
        keyword: "",

        layanan: (() => {
            const el = document.getElementById("layanan-data");
            if (!el) {
                console.warn("Element with ID 'layanan-data' not found");
                return [];
            }
            try {
                return JSON.parse(el.textContent);
            } catch (error) {
                console.error("Error parsing layanan data:", error);
                return [];
            }
        })(),

        kotaOptions: (() => {
            const el = document.getElementById("kota-data");
            if (!el) {
                console.warn("Element with ID 'kota-data' not found");
                return [];
            }
            try {
                return JSON.parse(el.textContent);
            } catch (error) {
                console.error("Error parsing kota data:", error);
                return [];
            }
        })(),

        form: {
            nama_lengkap: "",
            whatsapp: "",
            kota_id: "",
            nama_kota: "",
            maps: "",
            catatan: "",
            email: "",
            alamat: "",
            tanggal: "",
            waktu: "",
            promo: "",
            diskon: 0,
        },

        init() {
            if (this.form.kota_id) {
                this.updateNamaKota(this.form.kota_id);
            }

            this.form.tanggal = this.form.tanggal || this.getTomorrowDate();
            this.form.waktu = this.form.waktu || this.getCurrentTime();
        },

        getCurrentTime() {
            const now = new Date();
            return `${String(now.getHours()).padStart(2, "0")}:${String(
                now.getMinutes()
            ).padStart(2, "0")}`;
        },

        getTomorrowDate() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate());
            return tomorrow.toISOString().split("T")[0];
        },

        minDate() {
            return this.getTomorrowDate();
        },

        formatTanggal(tanggal) {
            if (!tanggal) return "";
            return new Date(tanggal).toLocaleDateString("id-ID", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            });
        },

        updateNamaKota(kotaId) {
            const kota = this.kotaOptions.find((k) => k.id_kota == kotaId);
            this.form.nama_kota = kota?.nama_kota || "";
            this.form.kota_id = kotaId;
        },

        filteredOptions() {
            const keyword = this.keyword.trim().toLowerCase();
            if (!keyword) return this.layanan;

            return this.layanan
                .map((root) => {
                    const rootMatch = root.nama_rootkategori
                        .toLowerCase()
                        .includes(keyword);
                    const subMatch = root.subkategori.filter((sub) =>
                        sub.nama_subkategori.toLowerCase().includes(keyword)
                    );

                    if (rootMatch || subMatch.length > 0) {
                        return {
                            ...root,
                            subkategori: rootMatch
                                ? root.subkategori
                                : subMatch,
                        };
                    }
                    return null;
                })
                .filter(Boolean);
        },

        handleClearSearch(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }

            this.keyword = "";

            this.showDropdown = true;

            this.$nextTick(() => {
                const input = this.$el.querySelector(
                    'input[x-model="keyword"]'
                );
                if (input) input.focus();
            });
        },

        handleEscapeKey(e) {
            if (e.key === "Escape") {
                this.handleClearSearch(e);
            }
        },

        addLayanan(id, subkategori) {
            if (!this.selectedLayanan.some((item) => item.id === id)) {
                this.selectedLayanan.push({
                    id,
                    nama_subkategori: subkategori.nama_subkategori,
                    harga: Number(subkategori.harga),
                    nama_rootkategori:
                        subkategori.root_kategori ||
                        this.getRootKategoriName(id),
                });
            }

            this.showDropdown = false;
        },

        removeLayanan(id) {
            this.selectedLayanan = this.selectedLayanan.filter(
                (l) => l.id !== id
            );
        },

        getRootKategoriName(subkategoriId) {
            const data = this.layanan;
            for (const root of data) {
                for (const sub of root.subkategori) {
                    if (sub.id === subkategoriId) {
                        return root.nama_rootkategori;
                    }
                }
            }
            return "Layanan";
        },

        totalHarga() {
            const total = this.selectedLayanan.reduce(
                (sum, item) => sum + Number(item.harga),
                0
            );
            return total - (this.form.diskon || 0);
        },

        isFormValid() {
            if (this.debugBypassValidation) {
                this.errorMessage = "";
                return true;
            }

            const f = this.form;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^(\+62|62|0)[0-9]{9,14}$/;

            if (!f.nama_lengkap) {
                this.errorMessage = "Nama lengkap wajib diisi.";
                return false;
            }

            if (!f.whatsapp) {
                this.errorMessage = "Nomor WhatsApp wajib diisi.";
                return false;
            }

            if (!phoneRegex.test(f.whatsapp)) {
                this.errorMessage =
                    "Nomor WhatsApp tidak valid. Gunakan format +62, 62, atau 0.";
                return false;
            }

            if (!f.kota_id) {
                this.errorMessage = "Kota wajib dipilih.";
                return false;
            }

            if (!f.email) {
                this.errorMessage = "Email wajib diisi.";
                return false;
            }

            if (!emailRegex.test(f.email)) {
                this.errorMessage = "Format email salah.";
                return false;
            }

            if (!f.alamat) {
                this.errorMessage = "Alamat wajib diisi.";
                return false;
            }

            if (!f.tanggal) {
                this.errorMessage = "Tanggal wajib diisi.";
                return false;
            }

            if (!f.waktu) {
                this.errorMessage = "Waktu wajib diisi.";
                return false;
            }

            if (this.selectedLayanan.length === 0) {
                this.errorMessage = "Minimal pilih 1 layanan.";
                return false;
            }

            this.errorMessage = "";
            return true;
        },

        async checkPromoCode() {
            const kode = this.form.promo.trim().toUpperCase();
            if (!kode) {
                this.form.diskon = 0;
                return;
            }

            try {
                const response = await fetch(`/promo/check?kode=${encodeURIComponent(kode)}`);
                if (!response.ok)
                    throw new Error("Kode promo tidak ditemukan.");

                const data = await response.json();
                this.form.diskon = Number(data.diskon);
                this.errorMessage = "";
            } catch (err) {
                this.form.diskon = 0;
                this.errorMessage = err.message;
            }
        },

        async submitForm() {
            if (this.isLoading) return;

            if (!this.isFormValid()) {
                this.errorMessage =
                    "Harap lengkapi semua field wajib dengan data yang valid.";
                return;
            }

            if (this.debugBypassValidation) {
                this.showBookingDetail = false;
                this.showSuccessModal = true;
                return;
            }

            const form = this.$refs.bookingForm;
            if (!form) {
                console.error("Form tidak ditemukan melalui x-ref.");
                this.errorMessage = "Terjadi kesalahan internal pada form.";
                return;
            }

            const formData = new FormData(form);

            this.isLoading = true;
            this.errorMessage = "";

            try {
                const response = await fetch(form.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": this.getCsrfToken(),
                    },
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(
                        data.errors
                            ? Object.values(data.errors).join(", ")
                            : data.message || "Terjadi kesalahan saat menyimpan data."
                    );
                }

                this.showSuccessModal = true;

                this.showBookingDetail = false;

                this.resetForm();

            } catch (err) {
                console.error("Submit error:", err);
                this.errorMessage = err.message;
            } finally {
                this.isLoading = false;
            }
        },

        openBookingDetail() {
            if (this.isFormValid()) {
                this.errorMessage = "";
                this.showBookingDetail = true;
            } else {
                this.showBookingDetail = false;
            }
        },

        prepareFormData() {
            const form = this.$refs.bookingForm;
            if (!form) {
                throw new Error("Formulir tidak ditemukan.");
            }

            const fd = new FormData(form);
            const f = this.form;

            fd.append("nama_lengkap", f.nama_lengkap);
            fd.append("whatsapp", f.whatsapp);
            fd.append("email", f.email);
            fd.append("kota", f.kota_id);
            fd.append("maps", f.maps || "");
            fd.append("alamat", f.alamat);
            fd.append("catatan", f.catatan || "");
            fd.append("tanggal", f.tanggal);
            fd.append("waktu", f.waktu);
            fd.append("promo", f.promo || "");
            fd.append("diskon", f.diskon || 0);

            this.selectedLayanan.forEach((layanan, index) => {
                fd.append(`layanan[${index}]`, layanan.id);
            });
            
            return new FormData(form);
        },

        resetForm() {
            this.form = {
                nama_lengkap: "",
                whatsapp: "",
                kota_id: "",
                nama_kota: "",
                maps: "",
                catatan: "",
                email: "",
                alamat: "",
                tanggal: this.getTomorrowDate(),
                waktu: this.getCurrentTime(),
                promo: "",
                diskon: 0,
            };
            this.selectedLayanan = [];
            this.errorMessage = "";
        },


        getCsrfToken() {
            const token = document.querySelector(
                'meta[name="csrf-token"]'
            )?.content;
            if (!token) {
                console.error("CSRF token not found");
                throw new Error("CSRF token tidak ditemukan.");
            }
            return token;
        },
    };
}
