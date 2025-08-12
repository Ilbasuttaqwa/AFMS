import { Head } from '@inertiajs/react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Users, Clock, DollarSign, Calendar, TrendingUp, AlertCircle } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface DashboardProps {
    auth: {
        user: {
            id: number;
            name: string;
            email: string;
            role: string;
        };
    };
    stats?: {
        total_karyawan: number;
        hadir_hari_ini: number;
        terlambat_hari_ini: number;
        total_bonus_bulan_ini: number;
        total_pinjaman_aktif: number;
    };
    recent_attendance?: Array<{
        id: number;
        karyawan: {
            nama: string;
            nip: string;
        };
        tanggal: string;
        waktu_masuk_pagi: string | null;
        waktu_keluar_pagi: string | null;
        menit_keterlambatan_pagi: number;
        status_pagi: string;
    }>;
}

export default function Dashboard({ auth, stats, recent_attendance }: DashboardProps) {
    // Default values jika data belum tersedia
    const defaultStats = {
        total_karyawan: 8,
        hadir_hari_ini: 6,
        terlambat_hari_ini: 2,
        total_bonus_bulan_ini: 5000000,
        total_pinjaman_aktif: 15000000,
    };

    const currentStats = stats || defaultStats;
    const attendanceData = recent_attendance || [];

    const getStatusBadge = (status: string, menit_terlambat: number) => {
        if (status === 'hadir' && menit_terlambat === 0) {
            return <Badge variant="default" className="bg-green-500">Tepat Waktu</Badge>;
        } else if (status === 'hadir' && menit_terlambat > 0) {
            return <Badge variant="destructive">Terlambat {menit_terlambat} menit</Badge>;
        } else if (status === 'alpha') {
            return <Badge variant="destructive">Alpha</Badge>;
        } else if (status === 'izin') {
            return <Badge variant="secondary">Izin</Badge>;
        } else if (status === 'sakit') {
            return <Badge variant="outline">Sakit</Badge>;
        }
        return <Badge variant="outline">{status}</Badge>;
    };

    const formatTime = (time: string | null) => {
        if (!time) return '-';
        return new Date(`2000-01-01 ${time}`).toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    const formatDate = (date: string) => {
        return new Date(date).toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard - AFMS" />
            
            <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
                {/* Welcome Section */}
                <div className="mb-4">
                    <h1 className="text-2xl font-bold text-gray-900">
                        Selamat datang di AFMS!
                    </h1>
                    <p className="text-gray-600">
                        Sistem Manajemen Absensi dan Keuangan Karyawan
                    </p>
                </div>

                {/* Stats Cards */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">
                                Total Karyawan
                            </CardTitle>
                            <Users className="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold">{currentStats.total_karyawan}</div>
                            <p className="text-xs text-muted-foreground">
                                Karyawan aktif
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">
                                Hadir Hari Ini
                            </CardTitle>
                            <Clock className="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-green-600">{currentStats.hadir_hari_ini}</div>
                            <p className="text-xs text-muted-foreground">
                                Dari {currentStats.total_karyawan} karyawan
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">
                                Terlambat Hari Ini
                            </CardTitle>
                            <AlertCircle className="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-red-600">{currentStats.terlambat_hari_ini}</div>
                            <p className="text-xs text-muted-foreground">
                                Karyawan terlambat
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">
                                Bonus Bulan Ini
                            </CardTitle>
                            <TrendingUp className="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-blue-600">
                                Rp {currentStats.total_bonus_bulan_ini.toLocaleString('id-ID')}
                            </div>
                            <p className="text-xs text-muted-foreground">
                                Total bonus
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">
                                Pinjaman Aktif
                            </CardTitle>
                            <DollarSign className="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-orange-600">
                                Rp {currentStats.total_pinjaman_aktif.toLocaleString('id-ID')}
                            </div>
                            <p className="text-xs text-muted-foreground">
                                Total pinjaman
                            </p>
                        </CardContent>
                    </Card>
                </div>

                {/* Recent Attendance */}
                <Card className="flex-1">
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <Calendar className="h-5 w-5" />
                            Ringkasan Sistem
                        </CardTitle>
                        <CardDescription>
                            Sistem AFMS telah berhasil disetup dengan data sample
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-4">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div className="p-4 border rounded-lg">
                                    <h3 className="font-medium mb-2">Fitur Tersedia:</h3>
                                    <ul className="text-sm text-gray-600 space-y-1">
                                        <li>• Manajemen Karyawan</li>
                                        <li>• Sistem Absensi</li>
                                        <li>• Manajemen Bonus</li>
                                        <li>• Manajemen Pinjaman</li>
                                        <li>• Laporan dan Dashboard</li>
                                    </ul>
                                </div>
                                <div className="p-4 border rounded-lg">
                                    <h3 className="font-medium mb-2">Data Sample:</h3>
                                    <ul className="text-sm text-gray-600 space-y-1">
                                        <li>• 8 Karyawan dengan berbagai golongan</li>
                                        <li>• 4 Lokasi kantor</li>
                                        <li>• 30 hari data absensi</li>
                                        <li>• Data bonus dan pinjaman</li>
                                        <li>• 3 User dengan role berbeda</li>
                                    </ul>
                                </div>
                            </div>
                            <div className="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <h3 className="font-medium text-blue-900 mb-2">Akun Login:</h3>
                                <div className="text-sm text-blue-800 space-y-1">
                                    <div><strong>Manager:</strong> manager@afms.com / password</div>
                                    <div><strong>Admin:</strong> admin@afms.com / password</div>
                                    <div><strong>HR:</strong> hr@afms.com / password</div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
