<!-- STUDENTS VIEW -->
<div class="page-header">
    <h1><i class="fas fa-user-graduate"></i>Liste des Étudiants</h1>
    <div style="display:flex;gap:12px;align-items:center;">
        <select class="form-select" id="filterStudentClass" onchange="filterStudentsByClass()" style="border-radius:50px;font-size:0.85rem;padding:0.45rem 1rem 0.45rem 1rem;min-width:160px;color:#374151;box-shadow:0 1px 2px rgba(0,0,0,0.02);border:1px solid #e5e7eb;font-weight:600;">
            <option value="" selected>Toutes les classes</option>
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
        <div class="search-bar" style="max-width:250px;">
            <i class="fas fa-search"></i>
            <input type="text" id="searchStudent" placeholder="Recherche..." onkeyup="searchStudents()">
        </div>
    </div>
</div>

<div class="dash-card">
    <div class="table-responsive">
        <table class="n-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom Complet</th>
                    <th>Classe & Section</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody id="tb-students">
                <!-- Loaded via AJAX -->
                <tr><td colspan="4" style="text-align:center;padding:3rem 0;color:#9ca3af;"><i class="fas fa-spinner fa-spin me-2"></i>Chargement...</td></tr>
            </tbody>
        </table>
    </div>
</div>
