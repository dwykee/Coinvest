@extends('layouts.app')

@section('title', 'Edit Wallet - ' . $wallet->name)

@section('content')
<style>
    .edit-container {
        max-width: 600px;
        margin: 0 auto;
        width: 100%;
    }

    .edit-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 32px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        color: #a19baf;
        text-decoration: none;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .back-btn:hover {
        background: rgba(255,255,255,0.06);
        color: #f5f3f7;
    }

    .edit-title {
        font-family: 'Sora', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: #f5f3f7;
        letter-spacing: -0.5px;
        margin: 0;
    }

    .form-section {
        background: rgba(18, 17, 24, 0.4);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 20px;
    }

    .section-title {
        font-family: 'Sora', sans-serif;
        font-size: 12px;
        font-weight: 700;
        color: #f5f3f7;
        margin: 0 0 20px 0;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group:last-of-type {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #a19baf;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 12px 14px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 10px;
        color: #f5f3f7;
        font-size: 14px;
        font-family: inherit;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }

    .form-input::placeholder {
        color: #7a7485;
    }

    .form-input:focus {
        border-color: rgba(139,92,246,0.5);
        box-shadow: 0 0 0 3px rgba(139,92,246,0.1);
    }

    .form-input:disabled {
        background: rgba(255,255,255,0.02);
        color: #7a7485;
        cursor: not-allowed;
    }

    .disabled-info {
        background: rgba(120,113,128,0.05);
        border: 1px solid rgba(120,113,128,0.2);
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 12px;
        color: #a19baf;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Profile Picture Styles */
    .profile-pic-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .profile-pic-preview {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 12px;
        background: rgba(255,255,255,0.03);
        border: 2px dashed rgba(139,92,246,0.3);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-pic-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pic-fallback {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #7a7485;
    }

    .pic-fallback-icon {
        font-size: 48px;
    }

    .pic-fallback-text {
        font-size: 13px;
    }

    .file-input-wrapper {
        position: relative;
    }

    .file-input {
        display: none;
    }

    .file-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px 16px;
        background: linear-gradient(180deg, #9f7aea 0%, #8b5cf6 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .file-label:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(139,92,246,0.4);
    }

    .delete-photo-btn {
        width: 100%;
        padding: 10px 14px;
        background: rgba(239, 68, 68, 0.1);
        color: #ffb4ab;
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 10px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .delete-photo-btn:hover {
        background: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
    }

    .info-box {
        background: rgba(139,92,246,0.05);
        border: 1px solid rgba(139,92,246,0.2);
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 12px;
        color: #c4b5fd;
        display: flex;
        gap: 8px;
        align-items: flex-start;
    }

    .info-icon {
        font-size: 16px;
        flex-shrink: 0;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
    }

    .btn {
        flex: 1;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(180deg, #9f7aea 0%, #8b5cf6 100%);
        color: white;
        box-shadow: 0 4px 14px rgba(139,92,246,0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139,92,246,0.4);
    }

    .btn-secondary {
        background: rgba(255,255,255,0.03);
        color: #a19baf;
        border: 1px solid rgba(255,255,255,0.07);
    }

    .btn-secondary:hover {
        background: rgba(255,255,255,0.06);
        color: #f5f3f7;
    }

    .error-text {
        color: #ffb4ab;
        font-size: 12px;
        margin-top: 6px;
    }
</style>

<div class="edit-container">
    <!-- Header -->
    <div class="edit-header">
        <a href="{{ route('wallets.index') }}" class="back-btn">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="edit-title">Edit Wallet</h1>
    </div>

    <!-- Form -->
    <form action="{{ route('wallets.update', $wallet->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Basic Info Section -->
        <div class="form-section">
            <h2 class="section-title">Wallet Information</h2>

            <div class="form-group">
                <label class="form-label" for="name">Wallet Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-input" 
                    value="{{ old('name', $wallet->name) }}" 
                    placeholder="e.g., My MetaMask" 
                    required
                >
                @error('name')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="provider">Provider</label>
                <input 
                    type="text" 
                    id="provider" 
                    class="form-input" 
                    value="{{ $wallet->provider }}" 
                    disabled
                >
                <div class="disabled-info">
                    <span class="material-symbols-outlined" style="font-size: 14px;">lock</span>
                    <span>Provider tidak bisa diubah setelah wallet dibuat</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="type">Type</label>
                <input 
                    type="text" 
                    id="type" 
                    class="form-input" 
                    value="{{ $wallet->type === 'wallet' ? 'Blockchain Wallet' : 'Exchange Account' }}" 
                    disabled
                >
            </div>
        </div>

        <!-- Profile Picture Section -->
        <div class="form-section">
            <h2 class="section-title">Profile Picture</h2>

            <div class="profile-pic-container">
                <!-- Preview -->
                <div class="profile-pic-preview" id="previewContainer">
                    @if($wallet->profile_picture && \Illuminate\Support\Facades\Storage::disk('public')->exists($wallet->profile_picture))
                        <img src="{{ asset('storage/' . $wallet->profile_picture) }}" alt="{{ $wallet->name }}">
                    @else
                        <div class="pic-fallback">
                            <span class="material-symbols-outlined pic-fallback-icon">image</span>
                            <span class="pic-fallback-text">No picture yet</span>
                        </div>
                    @endif
                </div>

                <!-- Upload -->
                <div class="file-input-wrapper">
                    <input 
                        type="file" 
                        id="picInput" 
                        name="profile_picture" 
                        class="file-input" 
                        accept="image/jpeg,image/png,image/gif" 
                        onchange="previewImage(this)"
                    >
                    <label for="picInput" class="file-label">
                        <span class="material-symbols-outlined">upload</span>
                        Upload Picture
                    </label>
                </div>

                <!-- Delete Button -->
                @if($wallet->profile_picture && \Illuminate\Support\Facades\Storage::disk('public')->exists($wallet->profile_picture))
                    <form action="{{ route('wallets.deletePhoto', $wallet->id) }}" method="POST" onsubmit="return confirm('Hapus profile picture?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-photo-btn">
                            <span class="material-symbols-outlined">delete</span>
                            Delete Picture
                        </button>
                    </form>
                @endif

                <!-- Info -->
                <div class="info-box">
                    <span class="material-symbols-outlined info-icon">info</span>
                    <span>Max 2MB. Format: JPG, PNG, atau GIF</span>
                </div>
            </div>

            @error('profile_picture')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <a href="{{ route('wallets.index') }}" class="btn btn-secondary">
                <span class="material-symbols-outlined">close</span>
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <span class="material-symbols-outlined">save</span>
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const file = input.files[0];
    const previewContainer = document.getElementById('previewContainer');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewContainer.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    }
}
</script>

@endsection
