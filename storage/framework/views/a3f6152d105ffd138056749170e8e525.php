<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — AbsensiKu</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0d0f14;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: #161928;
            border: 1px solid #2a2f42;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .logo-mark {
            width: 42px;
            height: 42px;
            background: #4f7cff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            color: white;
        }

        .logo-text { font-size: 20px; font-weight: 700; color: #f0f2ff; }
        .logo-sub { font-size: 11px; color: #6b7394; margin-top: 1px; }

        h2 { font-size: 22px; font-weight: 700; color: #f0f2ff; margin-bottom: 6px; }
        p { font-size: 13px; color: #6b7394; margin-bottom: 28px; }

        .form-group { margin-bottom: 18px; }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #a8afc8;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            background: #1e2436;
            border: 1px solid #2a2f42;
            border-radius: 10px;
            color: #f0f2ff;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus { border-color: #4f7cff; }
        input::placeholder { color: #6b7394; }

        .error {
            font-size: 12px;
            color: #f0436c;
            margin-top: 6px;
        }

        .btn {
            width: 100%;
            padding: 13px;
            background: #4f7cff;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.2s;
        }

        .btn:hover { background: #6690ff; transform: translateY(-1px); }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <div class="logo-mark">A</div>
            <div>
                <div class="logo-text">AbsensiKu</div>
                <div class="logo-sub">Sistem Absensi Karyawan</div>
            </div>
        </div>

        <h2>Selamat Datang</h2>
        <p>Masuk menggunakan NIP dan password kamu</p>

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label for="nip">NIP</label>
                <input
                    type="text"
                    id="nip"
                    name="nip"
                    placeholder="Masukkan NIP kamu"
                    value="<?php echo e(old('nip')); ?>"
                    autofocus
                >
                <?php $__errorArgs = ['nip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password kamu"
                >
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="btn">Masuk</button>
        </form>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/auth/login.blade.php ENDPATH**/ ?>