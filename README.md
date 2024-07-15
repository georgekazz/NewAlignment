## About new Alignment

Alignment is an application designed to address the challenges of ontology matching in diverse and open web environments. The introduction of the Directed Tree feature enhances the user experience by providing an intuitive visual representation of ontology structures and relationships. This interactive tool empowers users to better comprehend the connections and hierarchies within their data, facilitating more informed decision-making in ontology alignment processes. Alignment fosters collaborative and user-driven matching through configurable similarity algorithms. Data accessibility is ensured via SPARQL endpoints and an API, further supporting its utility in real-world applications.

## Requirements

- Composer
- PHP
- PHP capable web server
- MySQL
- Java 8

## Installation steps
<pre><code class="language-html">
# Clone the repo from Github
git clone -b main https://github.com/georgekazz/NewAlignment.git

# Run the Composer
composer install

# This will copy the contents of the .env.example file into the .env file.
copy .env.example .env

# Run the Migrations
php artisan migrate

# Seed the Database
php artisan db:seed

# Run the Server
php artisan serve
</code></pre>
