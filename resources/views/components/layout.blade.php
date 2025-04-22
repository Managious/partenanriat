<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our website</title>
    <style>
        :root {
            --primary-dark: #0118D8;
            --primary: #1B56FD;
            --neutral-light: #E9DFC3;
            --background: #FFF8F8;
            --text-dark: #333333;
        }
        
        body {
            background-color: var(--background);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-dark);
        }
        
        header {
            background-color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        nav h1 {
            color: var(--primary-dark);
            margin: 0;
        }
        
        nav a {
            color: var(--primary);
            text-decoration: none;
            margin-left: 1.5rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        nav a:hover {
            background-color: rgba(1, 24, 216, 0.1);
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        /* Dashboard Styles */
        .dashboard {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }
        
        .dashboard h1 {
            color: var(--primary-dark);
            margin-bottom: 1rem;
            font-size: 2rem;
        }
        
        .dashboard p {
            color: var(--text-dark);
            font-size: 1.1rem;
        }

        /* Specific styles for auth forms */
        .auth-form {
            background-color: white;
            padding: 2rem 3rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: 2rem auto;
        }
        
        .auth-form h1 {
            color: var(--primary-dark);
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .auth-form form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .auth-form label {
            color: var(--primary-dark);
            font-weight: 500;
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .auth-form input,
        .auth-form select {
            padding: 0.8rem;
            border: 1px solid var(--neutral-light);
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            font-size: 1rem;
        }
        
        .auth-form input:focus,
        .auth-form select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(27, 86, 253, 0.2);
        }
        
        .auth-form input[type="submit"] {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 1rem;
            font-size: 1rem;
        }
        
        .auth-form input[type="submit"]:hover {
            background-color: var(--primary-dark);
        }
        
        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--primary-dark);
        }
        
        .form-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
        // products.index

        .alert-success {
        padding: 1rem;
        background-color: #d4edda;
        color: #155724;
        border-radius: 5px;
        margin-bottom: 1.5rem;
        }

        .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1.5rem;
        }

        .table th {
        padding: 1rem;
        text-align: left;
        background-color: var(--primary);
        color: white;
        }

        .table td  {
        padding: 1rem;
        border-bottom: 1px solid var(--neutral-light);
        }

        .btn-warning {
        padding: 0.5rem 1rem;
        background-color: #7CF5FF;
        color: #000;
        text-decoration: none;
        border-radius: 5px;
        font-size: 0.9rem;
        }
        .btn-danger {
        padding: 0.5rem 1rem;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
        }

        .action-buttons {
        display: flex;
        gap: 0.5rem;
        }
        /* Ajoutez ces styles dans votre layout */
        .form-group {
        margin-bottom: 1rem;
        }

        .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--primary-dark);
        font-weight: 500;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid var(--neutral-light);
        border-radius: 5px;
        font-size: 1rem;
        }

        .form-group input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(27, 86, 253, 0.2);
        }

        .btn-submit {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 1rem 2rem;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        }

        .btn-submit:hover {
        background-color: var(--primary-dark);
        }
        /* Styles supplémentaires pour le bouton Annuler */
        .btn-cancel {
        display: inline-block;
        padding: 1rem 2rem;
        background-color: #f8f9fa;
        color: var(--primary-dark);
        border: 1px solid var(--neutral-light);
        border-radius: 5px;
        font-size: 1rem;
        font-weight: bold;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s;
        }

        .btn-cancel:hover {
        background-color: #e9ecef;
        border-color: var(--primary);
        }

    </style>
</head>
<body>
    <header>
        <nav>
            <h1>Bienvenue Madame/Monsieur</h1>
            <div>
                <a href="/application/login">Login</a>
                <a href="/application/logout">Logout</a>
                <a href="/application/register">Register</a>
                @if(request()->is('products*'))
                    <a href="/products/create" class="create-link">Create Product</a>
                @endif
            </div>
        </nav>
    </header>

    <main class="container">
        {{ $slot }}
    </main>
</body>
</html>