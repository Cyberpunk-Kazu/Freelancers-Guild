import './bootstrap';
import {
    createUserWithEmailAndPassword,
    getAuth,
    GoogleAuthProvider,
    sendPasswordResetEmail,
    sendEmailVerification,
    signInWithEmailAndPassword,
    signInWithPopup,
    signOut,
    updateProfile,
} from 'firebase/auth';
import { initializeApp } from 'firebase/app';

const firebaseApp = initializeApp({
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID,
});
const auth = getAuth(firebaseApp);

const authenticateWithLaravel = async (user) => {
    const response = await fetch('/auth/firebase', {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value
                ?? document.querySelector('meta[name="csrf-token"]')?.content
                ?? '',
        },
        body: JSON.stringify({ id_token: await user.getIdToken(true) }),
    });

    if (!response.ok) {
        const payload = await response.json().catch(() => ({}));
        const validationMessage = payload.errors
            ? Object.values(payload.errors).flat()[0]
            : null;

        throw new Error(validationMessage || payload.message || 'The guild could not verify your account.');
    }

    window.location.assign((await response.json()).redirect);
};

const displayFirebaseError = (error, exception) => {
    error.textContent = exception.code === 'auth/popup-closed-by-user'
        ? 'Google sign-in was cancelled.'
        : exception.message;
    error.classList.remove('hidden');
};

document.querySelector('[data-firebase-google]')?.addEventListener('click', async (event) => {
    const button = event.currentTarget;
    const error = document.querySelector('[data-firebase-error]');
    button.disabled = true;
    button.textContent = 'Opening Google...';
    error?.classList.add('hidden');

    try {
        const result = await signInWithPopup(auth, new GoogleAuthProvider());
        await authenticateWithLaravel(result.user);
    } catch (exception) {
        displayFirebaseError(error, exception);
        button.disabled = false;
        button.textContent = 'Continue with Google';
    }
});

document.querySelector('[data-firebase-login]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.currentTarget;
    const button = form.querySelector('button[type="submit"]');
    const error = document.querySelector('[data-firebase-error]');
    button.disabled = true;
    button.textContent = 'Entering the hall...';
    error?.classList.add('hidden');

    try {
        const result = await signInWithEmailAndPassword(auth, form.email.value, form.password.value);

        if (! result.user.emailVerified) {
            window.location.assign('/verify-email');
            return;
        }

        await authenticateWithLaravel(result.user);
    } catch (exception) {
        displayFirebaseError(error, exception);
        button.disabled = false;
        button.textContent = 'Sign In';
    }
});

document.querySelector('[data-firebase-register]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.currentTarget;
    const button = form.querySelector('button[type="submit"]');
    const error = form.querySelector('[data-firebase-error]');
    const password = form.password.value;
    button.disabled = true;
    button.textContent = 'Creating your record...';
    error?.classList.add('hidden');

    if (password.length < 10 || !/[A-Z]/.test(password) || !/[^A-Za-z0-9]/.test(password)) {
        error.textContent = 'Password must be at least 10 characters and include an uppercase letter and special character.';
        error.classList.remove('hidden');
        button.disabled = false;
        button.textContent = 'Create Account';
        return;
    }

    if (password !== form.password_confirmation.value) {
        error.textContent = 'Passwords do not match.';
        error.classList.remove('hidden');
        button.disabled = false;
        button.textContent = 'Create Account';
        return;
    }

    try {
        const result = await createUserWithEmailAndPassword(auth, form.email.value, password);
        await updateProfile(result.user, { displayName: form.name.value });
        await sendEmailVerification(result.user, {
            url: `${window.location.origin}/verify-email`,
        });
        window.location.assign('/verify-email');
    } catch (exception) {
        displayFirebaseError(error, exception);
        button.disabled = false;
        button.textContent = 'Create Account';
    }
});

document.querySelector('[data-firebase-check]')?.addEventListener('click', async (event) => {
    const button = event.currentTarget;
    const error = document.querySelector('[data-firebase-error]');
    button.disabled = true;
    button.textContent = 'Checking...';
    error?.classList.add('hidden');

    try {
        if (!auth.currentUser) {
            throw new Error('Your verification session expired. Please sign in again.');
        }

        await auth.currentUser.reload();

        if (!auth.currentUser.emailVerified) {
            throw new Error('Your email is not verified yet. Click the link in your email first.');
        }

        await authenticateWithLaravel(auth.currentUser);
    } catch (exception) {
        displayFirebaseError(error, exception);
        button.disabled = false;
        button.textContent = 'I Verified My Email';
    }
});

document.querySelector('[data-firebase-forgot]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.currentTarget;
    const button = form.querySelector('button[type="submit"]');
    const error = form.querySelector('[data-firebase-error]');
    const status = form.querySelector('[data-firebase-status]');
    button.disabled = true;
    button.textContent = 'Sending...';
    error?.classList.add('hidden');
    status?.classList.add('hidden');

    try {
        await sendPasswordResetEmail(auth, form.email.value, {
            url: `${window.location.origin}/login`,
        });
        status.textContent = 'If an account exists for that email, a password reset link has been sent.';
        status.classList.remove('hidden');
        form.reset();
    } catch (exception) {
        displayFirebaseError(error, exception);
    } finally {
        button.disabled = false;
        button.textContent = 'Send Reset Link';
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
