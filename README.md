# Simple Cloudflare Tools

**DNS Management Tool** ini dibuat untuk memudahkan saya sendiri dalam manajemen DNS domain yang terkoneksi ke Cloudflare, karena mempunyai banyak akun Cloudflare yang berbeda.

🔗 **Demo**: [demo-scf.utas.cc](https://demo-scf.utas.cc) (Full featured demo)

**Demo ini mencakup fungsi CRUD penuh, kecuali input Token jadi kalau ada yang dihapus tolong di input lagi datanya. Terimakasih**

## Fitur

- 🌐 **Multi Akun Cloudflare**: Mendukung pengelolaan beberapa akun Cloudflare secara bersamaan.
- 📜 **CURD DNS Record**: Menambahkan, mengedit, menghapus, dan melihat rekaman DNS.
- 🔍 **Pencarian Zone**: Memudahkan pencarian Domain.
- 🔎 **Pencarian Records**: Memudahkan pencarian DNS.
- ❌ **Bulk Delete Records**: Menghapus rekaman DNS secara massal.

## Instalasi

1. **Clone Repository**:
   ```bash
   git clone https://github.com/iodesk/simple-cloudflare-tools.git

2. ```bash
   mv simple-cloudflare-tools /var/www/html/

3. Buat token baru di akun Cloudflare seperti ini lalu masukan whitelist ip biar token lebih aman
 
   | Resources | Account/Zone | Permissions      | Access |
   |-----------|--------------|------------------|--------|
   | Resources | Account      | Account Settings | Read   |
   | Resources | Zone         | Zone Settings    | Edit   |
   | Resources | Zone         | Zone             | Edit   |
   | Resources | Zone         | Cache Purge      | Purge  |
   | Resources | Zone         | DNS              | Edit   |
   | Resources | Zone         | Analytics        | Read   |


5. Edit accounts.php tambahkan **Token** yang dibuat tadi, untuk email hanya sebagai penanda akun bisa diisi email atau nama lain.

6. Akses lewat browser localhost/simple-cloudflare-tools

## Keamanan
⚠️Jika menggunakan VPS/Hosting⚠️

   **Wajib Limit Akses** : Batasi akses ke aplikasi menggunakan IP, HTTP auth, atau aturan Cloudflare untuk meningkatkan keamanan.

## Tag
Cloudflare DNS Management, Multi Account Cloudflare, DNS Record Management, CRUD DNS Records, Zone Search Cloudflare, DNS Records Search, Bulk Delete DNS Records, Cloudflare API Integration, DNS Management Tool, Cloudflare Zone Management, PHP DNS Management Tool, Cloudflare Account Management, Cloudflare DNS API, DNS Record CRUD, DNS Tools, Domain Management,
