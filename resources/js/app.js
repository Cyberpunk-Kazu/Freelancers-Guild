import './bootstrap';
import { getAuth, GoogleAuthProvider, signInWithPopup } from 'firebase/auth';
import { initializeApp } from 'firebase/app';

const firebaseApp = initializeApp({
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID,
});

const googleButton = document.querySelector('[data-firebase-google]');

googleButton?.addEventListener('click', async () => {
    const error = document.querySelector('[data-firebase-error]');
    googleButton.disabled = true;
    googleButton.textContent = 'Opening Google...';
    error?.classList.add('hidden');

    try {
        const result = await signInWithPopup(getAuth(firebaseApp), new GoogleAuthProvider());
        const response = await fetch('/auth/firebase', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value ?? '',
            },
            body: JSON.stringify({ id_token: await result.user.getIdToken() }),
        });

        if (!response.ok) {
            throw new Error('The guild could not verify your Google account.');
        }

        window.location.assign((await response.json()).redirect);
    } catch (exception) {
        error.textContent = exception.code === 'auth/popup-closed-by-user'
            ? 'Google sign-in was cancelled.'
            : exception.message;
        error.classList.remove('hidden');
        googleButton.disabled = false;
        googleButton.textContent = 'Continue with Google';
    }
});

document.querySelector('[data-toggle-password]')?.addEventListener('click', (event) => {
    const button = event.currentTarget;
    const input = document.getElementById('password');

    if (!input) {
        return;
    }

    const isVisible = input.type === 'text';
    input.type = isVisible ? 'password' : 'text';
    button.textContent = isVisible ? 'SHOW' : 'HIDE';
});
