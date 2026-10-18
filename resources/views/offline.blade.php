    @extends('layout.master')

    @section('title')
        غير متصل
    @endsection

    @section('page_title')
        غير متصل
    @endsection

    @section('sub_main')
        حالة الاتصال
    @endsection

    @section('sub_title')
        لا يوجد اتصال بالإنترنت
    @endsection

    @section('css')
        <style>
            .offline-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .offline-card {
                background: white;
                border-radius: 20px;
                padding: 40px;
                text-align: center;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                max-width: 400px;
            }
            .offline-icon {
                font-size: 80px;
                margin-bottom: 20px;
            }
            .retry-btn {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                padding: 15px 40px;
                border-radius: 30px;
                color: white;
                font-size: 18px;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .retry-btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            }
        </style>
    @endsection

    @section('content')
        <div class="offline-container">
            <div class="offline-card">
                <div class="offline-icon">📶</div>
                <h2 class="mb-3">غير متصل</h2>
                <p class="text-muted mb-4">يبدو أنك غير متصل بالإنترنت. تأكد من اتصالك ثم حاول مرة أخرى.</p>
                <button class="retry-btn" onclick="location.reload()">
                    إعادة المحاولة
                </button>
            </div>
        </div>
    @endsection
