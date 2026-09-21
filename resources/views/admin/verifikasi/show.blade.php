<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Verifikasi</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fa;
            margin: 0;
            padding: 32px;
            color: #252525;
        }
        .container {
            max-width: 760px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e7e8ee;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            padding: 28px;
        }
        .title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 24px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }
        .field {
            background: #f9fafb;
            border: 1px solid #ececf1;
            border-radius: 12px;
            padding: 14px 16px;
        }
        .field label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #777;
            margin-bottom: 6px;
            font-weight: 700;
        }
        .field strong {
            font-size: 14px;
            color: #222;
        }
        .form-group {
            margin-top: 12px;
        }
        select {
            width: 100%;
            height: 44px;
            border: 1px solid #dfe1e8;
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
            background: #fff;
        }
        .actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }
        .btn {
            border: none;
            border-radius: 8px;
            padding: 12px 18px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-primary {
            background: #252A86;
            color: #fff;
        }
        .btn-secondary {
            background: #eef0f7;
            color: #333;
        }
        @media (max-width: 600px) {
            .grid { grid-template-columns: 1fr; }
            body { padding: 16px; }
            .container { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Detail Verifikasi</div>
        <div class="subtitle">Periksa data dan ubah status responden.</div>

        <div class="grid">
            <div class="field">
                <label>No. KK</label>
                <strong>{{ $item['no_kk'] }}</strong>
            </div>
            <div class="field">
                <label>NIK</label>
                <strong>{{ $item['nik'] }}</strong>
            </div>
            <div class="field">
                <label>Nama</label>
                <strong>{{ $item['nama'] }}</strong>
            </div>
            <div class="field">
                <label>Wilayah</label>
                <strong>{{ $item['wilayah'] }}</strong>
            </div>
            <div class="field">
                <label>Petugas</label>
                <strong>{{ $item['petugas'] }}</strong>
            </div>
            <div class="field">
                <label>Status Saat Ini</label>
                <strong>{{ $item['status_label'] }}</strong>
            </div>
        </div>

        <form
    action="{{ route('verifikasi.update', $item['id'] ?? 0) }}"
    method="POST"
>
    @csrf
    @method('PUT')

    <div class="form-group">
        <label
            for="status"
            style="display: block; margin-bottom: 8px; font-weight: 700; color: #333;"
        >
            Ubah Status
        </label>

        <select
            id="status"
            name="status"
            required
        >
            <option value="" disabled>
                Pilih Status Verifikasi
            </option>

            <option
                value="approved"
                {{ ($item['status'] ?? '') === 'approved' ? 'selected' : '' }}
            >
                Approved
            </option>

            <option
                value="rejected"
                {{ ($item['status'] ?? '') === 'rejected' ? 'selected' : '' }}
            >
                Rejected
            </option>
        </select>
    </div>

    <div class="actions">
        <button
            type="submit"
            class="btn btn-primary"
        >
            Simpan
        </button>

        <a
            href="{{ route('verifikasi.index') }}"
            class="btn btn-secondary"
        >
            Kembali
        </a>
    </div>
</form>

    </div>
</body>
</html>
