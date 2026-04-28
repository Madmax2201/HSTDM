<!-- NOTICES VIEW -->
<div class="page-header">
    <h1><i class="fas fa-bullhorn"></i>Gestion des Notices</h1>
    <button class="wbtn wbtn-primary" data-bs-toggle="modal" data-bs-target="#noticeModal">
        <i class="fas fa-plus"></i> Nouvelle Notice
    </button>
</div>

<div class="dash-card">
    <div class="table-responsive">
        <table class="n-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Titre</th>
                    <th>Destinataires</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Fichier</th>
                    <th style="text-align:right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($all_notices)): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-comment-slash"></i>
                            <h4>Aucune notice trouvée</h4>
                            <p>Créez une nouvelle notice pour en informer vos utilisateurs.</p>
                        </div>
                    </td>
                </tr>
                <?php else: foreach ($all_notices as $n): ?>
                <tr>
                    <td><strong style="color:#6b7280;"><?php echo $n['s_no']; ?></strong></td>
                    <td style="font-weight:600;color:#111827;"><?php echo htmlspecialchars($n['title']); ?></td>
                    <td>
                        <?php 
                        $role = $n['role'] === '' ? 'all' : $n['role'];
                        $rc = 'rt-all'; $rl = 'Tous';
                        if($role=='student'){ $rc='rt-student'; $rl='Étudiants ' . ($n['class'] ? '('.$n['class'].')' : ''); }
                        elseif($role=='teacher'){ $rc='rt-teacher'; $rl='Enseignants'; }
                        elseif($role=='admin'){ $rc='rt-admin'; $rl='Admin'; }
                        ?>
                        <span class="role-tag <?php echo $rc; ?>"><?php echo $rl; ?></span>
                    </td>
                    <td class="n-body-cell" title="<?php echo htmlspecialchars($n['body']); ?>">
                        <div style="max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo htmlspecialchars($n['body']); ?></div>
                    </td>
                    <td class="n-date-cell">
                        <span class="badge-status" style="background:#f3f4f6;color:#6b7280;">
                            <i class="fas fa-clock"></i> <?php echo date('d/m/Y H:i', strtotime($n['timestamp'])); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($n['file'] != ""): 
                            $fpath = '../noticeUploads/' . $n['file'];
                            if(file_exists($fpath)):
                        ?>
                            <a href="<?php echo $fpath; ?>" download class="action-btn btn-view" style="text-decoration:none;"><i class="fas fa-download"></i></a>
                        <?php else: ?>
                            <span style="color:#dc2626;font-size:0.75rem;">Introuvable</span>
                        <?php endif; else: ?>
                            <span style="color:#d1d5db;font-size:0.8rem;">—</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:right;">
                        <button class="action-btn btn-reject" onclick="deleteNotice(<?php echo $n['s_no']; ?>)" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Creation Notice -->
<div class="modal fade" id="noticeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background:#f9fafb;border-bottom:1px solid #e5e7eb;border-top-left-radius:16px;border-top-right-radius:16px;padding:16px 24px;">
                <h5 class="modal-title" style="font-weight:700;font-size:1.1rem;color:#111827;">
                    <i class="fas fa-paper-plane me-2" style="color:#3b82f6;"></i>Envoyer une Notice
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:0.85rem;color:#374151;">Destinataires</label>
                    <select class="form-select" id="noticeRole" onchange="checkNoticeClass()" style="border-radius:8px;font-size:0.9rem;padding:0.6rem 1rem;">
                        <option value="all">Tous</option>
                        <option value="student">Étudiants</option>
                        <option value="teacher">Enseignants</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="mb-3" id="noticeClassWrap" style="display:none;">
                    <label class="form-label fw-bold" style="font-size:0.85rem;color:#374151;">Classe spécifique (Optionnel)</label>
                    <select class="form-select" id="noticeClass" style="border-radius:8px;font-size:0.9rem;padding:0.6rem 1rem;">
                        <option value="">-- Toutes les classes --</option>
                        <option value="12m">12 (Math)</option>
                        <option value="12b">12 (Bio)</option>
                        <option value="12c">12 (Commerce)</option>
                        <option value="11m">11 (Math)</option>
                        <option value="11b">11 (Bio)</option>
                        <option value="11c">11 (Commerce)</option>
                        <option value="10">10</option>
                        <option value="9">9</option>
                        <option value="8">8</option>
                        <option value="7">7</option>
                        <option value="6">6</option>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                        <option value="pg">pg</option>
                        <option value="lkg">lkg</option>
                        <option value="ukg">ukg</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:0.85rem;color:#374151;">Titre de la notice</label>
                    <input type="text" id="noticeTitle" class="form-control" placeholder="Titre clair et concis..." style="border-radius:8px;font-size:0.9rem;padding:0.6rem 1rem;">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:0.85rem;color:#374151;">Message</label>
                    <textarea id="noticeMessage" class="form-control" rows="4" placeholder="Contenu de votre notice..." style="border-radius:8px;font-size:0.9rem;padding:0.6rem 1rem;resize:none;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #e5e7eb;padding:16px 24px;background:#f9fafb;border-bottom-left-radius:16px;border-bottom-right-radius:16px;">
                <button type="button" class="btn" data-bs-dismiss="modal" style="font-weight:600;color:#6b7280;font-size:0.9rem;">Annuler</button>
                <button type="button" class="btn" id="btnSendNotice" style="background:#3b82f6;color:#fff;border-radius:8px;padding:0.5rem 1.25rem;font-weight:600;font-size:0.9rem;box-shadow:0 4px 10px rgba(59,130,246,0.25);" onclick="sendNotice(this)"><i class="fas fa-paper-plane me-2"></i>Envoyer</button>
            </div>
        </div>
    </div>
</div>
