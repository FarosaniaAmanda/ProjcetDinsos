@extends('admin.layouts.app')

@section('title', 'Detail Jawaban Anggota Keluarga')

@push('styles')
<style>
    .member-answer-page {
        min-height: calc(100vh - 70px);
        padding: 24px 32px 40px;
        background: #f5f7fb;
    }

    .member-answer-card {
        max-width: 1100px;
        margin: 0 auto;
        padding: 26px;
        border: 1px solid #e7e9f0;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .04);
    }

    .member-answer-back {
        display: inline-flex;
        margin-bottom: 18px;
        color: #252a86;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
    }

    .member-answer-title {
        margin: 0 0 6px;
        color: #252a86;
        font-size: 24px;
    }

    .member-answer-subtitle {
        margin: 0;
        color: #73798a;
        font-size: 13px;
        line-height: 1.6;
    }

    .member-answer-summary,
    .member-answer-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .member-answer-summary {
        margin: 22px 0;
        padding: 18px;
        border: 1px solid #e8eaf1;
        border-radius: 12px;
        background: #fafbff;
    }

    .member-answer-summary-item span,
    .member-answer-item span {
        display: block;
        margin-bottom: 5px;
        color: #7a8090;
        font-size: 11px;
        font-weight: 600;
    }

    .member-answer-summary-item strong {
        color: #30364a;
        font-size: 13px;
    }

    .member-answer-section-title {
        margin: 0 0 12px;
        color: #30364a;
        font-size: 16px;
    }

    .member-answer-item {
        min-width: 0;
        padding: 14px;
        border: 1px solid #e8eaf1;
        border-radius: 10px;
        background: #fff;
    }

    .member-answer-item strong {
        color: #30364a;
        font-size: 13px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    @media (max-width: 700px) {
        .member-answer-page {
            padding: 16px 12px 28px;
        }

        .member-answer-card {
            padding: 18px 14px;
        }

        .member-answer-summary,
        .member-answer-grid {
            grid-template-columns: 1fr;
        }

        .member-answer-title {
            font-size: 20px;
        }
    }
</style>
@endpush

@section('content')
<main class="member-answer-page">
    <article class="member-answer-card">
        <a class="member-answer-back" href="{{ route('verifikasi.index') }}">
            &larr; Kembali ke Verifikasi
        </a>

        <h1 class="member-answer-title">Detail Jawaban Anggota Keluarga</h1>
        <p class="member-answer-subtitle">
            Jawaban kuisioner untuk anggota keluarga di bawah ini.
        </p>

        <section class="member-answer-summary" aria-label="Identitas anggota">
            <div class="member-answer-summary-item">
                <span>Nama Kepala Keluarga</span>
                <strong>{{ $responden['nama'] }}</strong>
            </div>
            <div class="member-answer-summary-item">
                <span>Nomor Kartu Keluarga</span>
                <strong>{{ $responden['no_kk'] }}</strong>
            </div>
            <div class="member-answer-summary-item">
                <span>Nama Anggota</span>
                <strong>{{ $member['nama'] }}</strong>
            </div>
            <div class="member-answer-summary-item">
                <span>Hubungan Keluarga</span>
                <strong>{{ $member['status_keluarga'] }}</strong>
            </div>
        </section>

        <section aria-labelledby="memberAnswersTitle">
            <h2 class="member-answer-section-title" id="memberAnswersTitle">
                Jawaban Kuisioner
            </h2>

            <div class="member-answer-grid">
                @forelse ($member['questions'] as $question)
                    <article class="member-answer-item">
                        <span>{{ $question['text'] }}</span>
                        <strong>{{ $question['answer'] }}</strong>
                    </article>
                @empty
                    <p>Belum ada jawaban kuisioner untuk anggota ini.</p>
                @endforelse
            </div>
        </section>
    </article>
</main>
@endsection
