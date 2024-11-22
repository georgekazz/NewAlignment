<style>
    .menu-item a {
        color: white;
        text-decoration: none;
    }

    .menu {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .menu-item {
        width: 300px;
        height: 200px;
        margin: 20px;
        max-width: 300px;
        background-color: #2C436C;
        color: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease;
    }

    .menu-item p {
        margin-bottom: 5px;
    }

    .menu-item h6 {
        margin-top: 30px;
    }

    .menu-item:hover {
        background-color: #D75603;
    }

    @media (max-width: 768px) {
        .menu-item {
            max-width: 100%;
        }
    }

    footer {
        background-color: #f8f9fa;
        padding: 20px;
        text-align: center;
        width: 100%;
        margin-top: auto;
    }
</style>

<link href="../public/css/AdminLTE.css" rel="stylesheet">

<div class="container spark-screen">
    <div class="row">
        <div class="menu">
            <div class="menu-item">
                <h3>Profile</h3>
                <h6>Show the User Profile</h6>
                <a href="/newalignment/public/admin/profile">Click for more</a>
            </div>
            <div class="menu-item">
                <h3>Directed Tree</h3>
                <h6>Go to the Directed Tree</h6>
                <a href="#">Click for more</a>
            </div>
            <div class="menu-item">
                <h3>Ontologies</h3>
                <h6>Upload or Edit an Ontology</h6>
                <a href="/newalignment/public/admin/mygraphs">Click for more</a>
            </div>
            <div class="menu-item">
                <h3>Projects</h3>
                <h6>Create or edit a Project and start creating Links</h6>
                <a href="/newalignment/public/admin/myprojects">Click for more</a>
            </div>
            <div class="menu-item">
                <h3>My Links</h3>
                <h6>Import, Export, Delete or Review your links</h6>
                <a href="#">Click for more</a>
            </div>
            <div class="menu-item">
                <h3>Comparison Settings</h3>
                <h6>Fine Tune Suggestion Provider Settings</h6>
                <a href="/newalignment/public/admin/settings">Click for more</a>
            </div>
        </div>
    </div>
</div>

<footer class="py-4 bg-gray-200 text-center">
    <p class="text-gray-600">&copy; {{ date('Y') }} Georgios Christoforos Kazlaris. All rights reserved. Developed with Open
        Knowledge Foundation Greece.</p>
</footer>