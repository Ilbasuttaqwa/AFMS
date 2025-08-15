# AFMS - Deployment ke Vercel

## Persiapan Deployment

### 1. Konfigurasi Environment Variables di Vercel

Setelah deploy ke Vercel, tambahkan environment variables berikut di dashboard Vercel:

```
APP_NAME=AFMS
APP_ENV=production
APP_KEY=base64:Y52Me5qg1IgyalnV2CgC7JgS38eprdcDDgJi+O4LZvE=
APP_DEBUG=false
APP_URL=https://your-app.vercel.app

LOG_CHANNEL=stderr
LOG_LEVEL=error

# Database Configuration (gunakan database cloud seperti PlanetScale, Railway, atau Supabase)
DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-username
DB_PASSWORD=your-password

SESSION_DRIVER=array
CACHE_DRIVER=array

# Google OAuth (opsional)
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URL=https://your-app.vercel.app/callback
```

### 2. Database Setup

Untuk production, disarankan menggunakan database cloud:

#### Option 1: PlanetScale (MySQL)
1. Buat akun di [PlanetScale](https://planetscale.com/)
2. Buat database baru
3. Dapatkan connection string
4. Update environment variables

#### Option 2: Railway (PostgreSQL/MySQL)
1. Buat akun di [Railway](https://railway.app/)
2. Deploy database
3. Dapatkan connection details
4. Update environment variables

#### Option 3: Supabase (PostgreSQL)
1. Buat akun di [Supabase](https://supabase.com/)
2. Buat project baru
3. Dapatkan database URL
4. Update environment variables

### 3. Langkah Deployment

1. **Push ke GitHub**
   ```bash
   git add .
   git commit -m "Prepare for Vercel deployment"
   git push origin master
   ```

2. **Deploy ke Vercel**
   - Login ke [Vercel](https://vercel.com/)
   - Import project dari GitHub
   - Pilih repository `AFMS`
   - Vercel akan otomatis detect sebagai PHP project
   - Set environment variables
   - Deploy

3. **Setelah Deployment**
   - Jalankan migration database (jika diperlukan)
   - Test semua fitur aplikasi
   - Update URL di Google OAuth settings

### 4. File Penting untuk Vercel

- `vercel.json` - Konfigurasi deployment Vercel
- `api/index.php` - Entry point untuk Vercel
- `.env.example` - Template environment variables

### 5. Troubleshooting

#### Jika ada error 500:
1. Check logs di Vercel dashboard
2. Pastikan semua environment variables sudah diset
3. Pastikan database connection berfungsi

#### Jika assets tidak load:
1. Pastikan path assets sudah benar
2. Check konfigurasi `APP_URL`

#### Jika session tidak berfungsi:
1. Gunakan `SESSION_DRIVER=array` untuk serverless
2. Atau gunakan database session dengan database cloud

### 6. Optimasi Performance

1. **Caching**
   - Gunakan `CACHE_DRIVER=array` untuk serverless
   - Atau gunakan Redis cloud untuk persistent cache

2. **Database**
   - Gunakan connection pooling
   - Optimasi query database
   - Gunakan database indexing

3. **Assets**
   - Compress images
   - Minify CSS/JS
   - Gunakan CDN untuk static assets

## Catatan Penting

- Vercel menggunakan serverless functions, jadi beberapa fitur Laravel mungkin perlu disesuaikan
- Session dan cache sebaiknya menggunakan driver yang kompatibel dengan serverless
- Database harus menggunakan cloud database, bukan local database
- File uploads sebaiknya menggunakan cloud storage (AWS S3, Cloudinary, dll)

## Support

Jika ada masalah dengan deployment, check:
1. Vercel documentation untuk PHP
2. Laravel documentation untuk deployment
3. GitHub issues di repository ini