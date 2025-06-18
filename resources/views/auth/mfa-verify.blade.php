<!DOCTYPE html>
<html>
<head>
    <title>Verify MFA Code</title>
</head>
<body>
    <h2>Multi-Factor Authentication</h2>

    <form method="POST" action="{{ route('mfa.verify.submit') }}">
        @csrf
        <label for="code">Enter the code sent to your email:</label><br>
        <input type="text" name="code" id="code" required>
        @error('code') <div>{{ $message }}</div> @enderror
        <br><br>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
