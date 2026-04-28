<!-- PASSWORD VIEW -->
<div class="page-header">
    <h1><i class="fas fa-key"></i>Changer le Mot de passe</h1>
    <span style="font-size:0.85rem;color:#6b7280;"><i class="fas fa-shield-alt me-1"></i>Sécurité de compte</span>
</div>

<div style="display:flex;justify-content:center;margin-top:2rem;">
    <div class="dash-card" style="width:100%;max-width:500px;padding:3rem 2.5rem;text-align:left;">
        <div style="text-align:center;margin-bottom:2rem;">
            <div style="width:64px;height:64px;background:#eff6ff;color:#3b82f6;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin:0 auto 1rem;">
                <i class="fas fa-lock"></i>
            </div>
            <h2 style="font-size:1.4rem;font-weight:800;color:#111827;margin-bottom:0.25rem;">Réinitialiser</h2>
            <p style="color:#6b7280;font-size:0.9rem;margin:0;">Assurez-vous d'utiliser un mot de passe sécurisé.</p>
        </div>

        <form id="formChangePassword" onsubmit="changePassword(event)">
            <div style="margin-bottom:1.25rem;">
                <label style="display:block;font-size:0.85rem;font-weight:700;color:#374151;margin-bottom:0.5rem;"><i class="fas fa-lock-open me-2" style="color:#9ca3af;"></i>Mot de passe actuel</label>
                <input type="password" id="cp_current" class="form-control" style="border-radius:8px;padding:0.75rem 1rem;border:1px solid #e5e7eb;font-size:0.95rem;background:#f9fafb;" required>
            </div>
            
            <div style="margin-bottom:1.25rem;">
                <label style="display:block;font-size:0.85rem;font-weight:700;color:#374151;margin-bottom:0.5rem;"><i class="fas fa-key me-2" style="color:#9ca3af;"></i>Nouveau mot de passe</label>
                <input type="password" id="cp_new" class="form-control" style="border-radius:8px;padding:0.75rem 1rem;border:1px solid #e5e7eb;font-size:0.95rem;background:#f9fafb;" required>
            </div>
            
            <div style="margin-bottom:2.25rem;">
                <label style="display:block;font-size:0.85rem;font-weight:700;color:#374151;margin-bottom:0.5rem;"><i class="fas fa-check-double me-2" style="color:#9ca3af;"></i>Répéter le mot de passe</label>
                <input type="password" id="cp_repeat" class="form-control" style="border-radius:8px;padding:0.75rem 1rem;border:1px solid #e5e7eb;font-size:0.95rem;background:#f9fafb;" required>
            </div>
            
            <button type="submit" id="cp_btn" style="width:100%;background:#3b82f6;color:white;font-weight:700;border:none;border-radius:8px;padding:0.9rem;font-size:0.95rem;transition:all 0.2s;box-shadow:0 4px 14px rgba(59,130,246,0.3);">
                <i class="fas fa-save me-2"></i>Mettre à jour
            </button>
        </form>
    </div>
</div>
