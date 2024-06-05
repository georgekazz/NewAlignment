<style>
    .user-table {
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
    }

    .user-row {
        display: flex;
        flex-direction: column;
        border-bottom: 1px solid #ddd;
    }

    .user-label,
    .user-value {
        padding: 15px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .user-label {
        background-color: #3498db;
        color: #fff;
        font-weight: bold;
        border-right: 1px solid #ddd;
    }

    .user-value {
        background-color: #f8f9fa;
    }

    .user-profile-image {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .user-profile-image img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
</style>

<div class="user-table">
    <div class="user-row">
        <div class="user-label">Profile Image</div>
        <div class="user-value">
            <div class="user-profile-image">
                <img src="{{ $avatar }}" alt="Profile Image">
            </div>
        </div>
    </div>
    <div class="user-row">
        <div class="user-label">Όνομα</div>
        <div class="user-value">{{ $name }}</div>
    </div>
    <div class="user-row">
        <div class="user-label">Created At</div>
        <div class="user-value">{{ $createdAt }}</div>
    </div>
</div>
