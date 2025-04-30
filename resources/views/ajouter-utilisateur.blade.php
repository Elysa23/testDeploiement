<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur</title>
</head>
<body>
    <h1>Ajouter un nouvel utilisateur</h1>

    <form action="/ajouter-utilisateur" method="POST">
    @csrf

    <!--Message de succès--> 
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <div class="mb-3">
        <label for="name" class="form-label">Nom</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="role" class="form-label">Rôle</label>
        <select name="role" id="role" class="form-select" required>
            <option value="apprenant">Apprenant</option>
            <option value="formateur">Formateur</option>
            <option value="admin">Administrateur</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Ajouter l'utilisateur</button>
</form>



</body>
</html>
