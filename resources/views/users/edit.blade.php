{{-- Formulario independiente para editar usuario y rol. --}}
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Editar usuario</title>
    <style>
        :root {
            --bg: #0f172a;
            --card: #0b1224;
            --muted: #9aa4bf;
            --text: #e5e7eb;
            --line: #18233f;
            --primary: #2563eb;
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            background: #0b1022;
            color: var(--text);
            font-family: system-ui, Arial
        }

        header {
            display: flex;
            gap: 10px;
            align-items: center;
            padding: 16px;
            border-bottom: 1px solid var(--line);
            background: #0b1022
        }

        header a.btn {
            margin-left: auto
        }

        main {
            max-width: 720px;
            margin: 20px auto;
            padding: 0 16px
        }

        .card {
            background: linear-gradient(180deg, rgba(11, 18, 36, .9), rgba(11, 18, 36, .75));
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 16px
        }

        label {
            display: block;
            margin-top: 10px;
            color: #cbd5e1
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #334467;
            background: #0b1022;
            color: #e5e7eb;
            margin-top: 6px
        }

        .hint {
            color: var(--muted);
            font-size: 12px;
            margin-top: 6px
        }

        .btn {
            margin-top: 14px;
            display: inline-block;
            padding: 10px 14px;
            border-radius: 10px;
            text-decoration: none;
            border: 1px solid #334467;
            background: #1a2646;
            color: #fff
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary)
        }

        .err {
            color: #ef4444;
            font-size: 13px;
            margin-top: 4px
        }
    </style>
</head>

<body>
    <header>
        <h1>Editar usuario</h1>
        <a class="btn" href="{{ route('users.index') }}">← Volver</a>
    </header>
    <main>
        <div class="card">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf @method('PUT')
                <label>Nombre</label>
                <input name="name" value="{{ old('name', $user->name) }}">
                @error('name')
                    <div class="err">{{ $message }}</div>
                @enderror

                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}">
                @error('email')
                    <div class="err">{{ $message }}</div>
                @enderror

                <label>Nueva contraseña (opcional)</label>
                <input type="password" name="password">
                <div class="hint">Déjalo en blanco para mantener la contraseña actual.</div>
                @error('password')
                    <div class="err">{{ $message }}</div>
                @enderror

                <label>Rol</label>
                @php $current = $user->getRoleNames()->first(); @endphp
                <select name="role">
                    @foreach ($roles as $r)
                        <option value="{{ $r }}" @selected(old('role', $current) === $r)>{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
                @error('role')
                    <div class="err">{{ $message }}</div>
                @enderror

                <button class="btn btn-primary" type="submit">Actualizar</button>
            </form>
        </div>
    </main>
</body>

</html>
