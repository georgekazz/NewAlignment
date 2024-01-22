<style>
    .user-table {
        border: 1px solid #ccc;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .user-row {
        display: flex;
        border-bottom: 1px solid #ccc;
    }

    .user-label,
    .user-value {
        padding: 10px;
        flex: 1;
    }

    .user-label {
        background-color: #3498db;
        color: #fff;
        font-weight: bold;
    }

    .user-value {
        background-color: #ecf0f1;
    }
</style>

<div class="user-table">
    <div class="user-row">
        <div class="user-label">Profile Image</div>
        <div class="user-value">{{ $avatar }}</div>
    </div>
    <div class="user-row">
        <div class="user-label">Όνομα:</div>
        <div class="user-value">{{ $name }}</div>
    </div>
    <div class="user-row">
        <div class="user-label">Created At:</div>
        <div class="user-value">{{ $createdAt }}</div>
    </div>
</div>