<div class="user-header">
    @auth
        <h2>Add data to <span class="username">{{ auth()->user()->name }}</span>!</h2>
    @endauth
</div>

<form action="{{ route('accounts.import') }}" method="POST" class="modern-form">
    @csrf
    <div class="form-group">
        <label for="import_list" class="form-label">Accounts List</label>
        <textarea name="import_list" id="import_list" class="form-textarea" rows="8" required placeholder="Paste your list here"></textarea>
        <small class="form-hint">Format: full_name|first_name|last_name|date|month|year|gender|email|password</small>
    </div>
    
    <div class="form-group">
        <label for="field_separator" class="form-label">Field Separator</label>
        <input type="text" name="field_separator" id="field_separator" class="form-input" value="|" maxlength="1" required>
    </div>
    
    <div class="form-group checkbox-group">
        <input type="checkbox" name="skip_header" id="skip_header" value="1" checked>
        <label for="skip_header" class="checkbox-label">Skip Header</label>
    </div>
    
    <button type="submit" class="submit-button">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="17 8 12 3 7 8"></polyline>
            <line x1="12" y1="3" x2="12" y2="15"></line>
        </svg>
        Import Accounts
    </button>
</form>

<style>
    .user-header {
        background: #6366f1;
        color: white;
        padding: 1.2rem 1rem;
        text-align: center;
        margin-bottom: 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .user-header h2 {
        font-size: 1.3rem;
        margin: 0;
        font-weight: 600;
        color: white;
    }
    
    .username {
        font-weight: 700;
        text-decoration: underline;
    }
    
    .modern-form {
        max-width: 100%;
        margin: 0;
        padding: 1rem;
        background: #ffffff;
        border-radius: 0;
        box-shadow: none;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #374151;
        font-size: 0.9rem;
    }
    
    .form-textarea {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background-color: #f9fafb;
        font-family: monospace;
        transition: all 0.2s ease;
        resize: vertical;
        font-size: 0.85rem;
        min-height: 150px;
    }
    
    .form-input {
        width: 40px;
        padding: 0.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background-color: #f9fafb;
        text-align: center;
        font-size: 0.85rem;
    }
    
    .form-hint {
        display: block;
        margin-top: 0.5rem;
        color: #6b7280;
        font-size: 0.75rem;
    }
    
    .checkbox-group {
        display: flex;
        align-items: center;
    }
    
    .checkbox-label {
        margin-left: 0.5rem;
        color: #374151;
        font-size: 0.9rem;
    }
    
    input[type="checkbox"] {
        width: 16px;
        height: 16px;
    }
    
    .submit-button {
        background-color: #6366f1;
        color: white;
        border: none;
        padding: 0.8rem;
        border-radius: 8px;
        font-weight: 500;
        width: 100%;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    @media (min-width: 768px) {
        .user-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 12px 12px 0 0;
        }
        
        .user-header h2 {
            font-size: 1.5rem;
        }
        
        .modern-form {
            padding: 2rem;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 0 auto 2rem;
            max-width: 800px;
        }
        
        .form-textarea {
            font-size: 0.9rem;
            padding: 1rem;
        }
    }
</style>