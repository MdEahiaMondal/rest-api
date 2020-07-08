Hello {{ $user->name }}.
You changed  your email. Please verify your new email using this link:
{{ route('verify', $user->verification_token) }}
