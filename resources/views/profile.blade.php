@extends('layouts.app')

@section('title', 'My Profile')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/profilestyle.css') }}">
@endsection

@section('content')
    <div class="profile-header">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/elements/logo.png') }}" alt="Vinylopia Logo" class="logo">
        </a>
    </div>

    <div class="container">
        <div class="profile-card">
            <div class="profile-top">
                <div class="avatar-section">
                    <img src="{{ asset(Auth::user()->avatar ? 'storage/' . Auth::user()->avatar : 'images/avatars/default-avatar.png') }}" alt="Avatar" class="avatar" id="avatar-image">
                    <h2 class="username">{{ Auth::user()->username }}</h2>
                    <p class="member-since">Member since {{ Auth::user()->created_at->format('F Y') }}</p>
                </div>
                <div class="profile-actions">
                    <a href="{{ route('orders') }}" class="profile-btn">Order History</a>
                    <a href="{{ route('listing.create') }}" class="profile-btn">List a Vinyl</a>
                </div>
            </div>

            <div class="profile-info">
                <h3 class="section-title">Personal Information</h3>
                <div class="info-grid">
                    <div><strong>First Name:</strong> {{ Auth::user()->fname }}</div>
                    <div><strong>Last Name:</strong> {{ Auth::user()->lname }}</div>
                    <div><strong>Email:</strong> {{ Auth::user()->email }}</div>
                    <div><strong>Country:</strong> {{ Auth::user()->country->name }}</div>
                    <div><strong>Address:</strong> {{ Auth::user()->address }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Avatar Modal -->
    <div id="avatarModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="modal-title">Update Your Avatar</h2>
            <form action="{{ route('avatar.upload') }}" method="POST" enctype="multipart/form-data" class="avatar-form">
                @csrf
                <div class="avatar-preview">
                    <label for="avatar-upload-modal" class="avatar-label">
                        <img id="avatar-preview-img" src="{{ asset(Auth::user()->avatar ? 'storage/' . Auth::user()->avatar : 'images/avatars/default-avatar.png') }}" alt="Avatar" class="avatar-preview-img">
                        <div class="hover-text">Click to change</div>
                    </label>
                    <input type="file" id="avatar-upload-modal" name="avatar" accept="image/*" style="display:none;" onchange="previewAvatar(event)">
                </div>
                <div class="button-container">
                    <button type="submit" class="confirm-btn">Upload Avatar</button>
                </div>
            </form>
            <form action="{{ route('avatar.delete') }}" method="post" class="delete-form">
                @csrf
                <div class="button-container">
                    <button type="submit" class="delete-btn">Delete Avatar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        var modal = document.getElementById("avatarModal");
        var img = document.getElementById("avatar-image");
        img.onclick = function() {
            modal.style.display = "block";
        }

        var span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            modal.style.display = "none";
            resetAvatarPreview();
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                resetAvatarPreview();
            }
        }

        function previewAvatar(event) {
            var file = event.target.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview-img').src = e.target.result;
            }
            if (file) reader.readAsDataURL(file);
        }

        function resetAvatarPreview() {
            document.getElementById('avatar-preview-img').src = "{{ asset(Auth::user()->avatar ? 'storage/' . Auth::user()->avatar : 'images/avatars/default-avatar.png') }}";
            document.getElementById('avatar-upload-modal').value = "";
        }
    </script>
@endsection
