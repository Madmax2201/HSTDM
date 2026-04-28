<!-- TEACHERS VIEW -->
<div class="page-header">
    <h1><i class="fas fa-chalkboard-teacher"></i>Liste des Enseignants</h1>
    <div class="search-bar" style="max-width:300px;">
        <i class="fas fa-search"></i>
        <input type="text" id="searchTeacher" placeholder="Rechercher un enseignant..." onkeyup="searchTeachers()">
    </div>
</div>

<div class="dash-card">
    <div class="table-responsive">
        <table class="n-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom Complet</th>
                    <th>Genre</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody id="tb-teachers">
                <!-- Loaded via AJAX -->
                <tr><td colspan="4" style="text-align:center;padding:3rem 0;color:#9ca3af;"><i class="fas fa-spinner fa-spin me-2"></i>Chargement...</td></tr>
            </tbody>
        </table>
    </div>
</div>
