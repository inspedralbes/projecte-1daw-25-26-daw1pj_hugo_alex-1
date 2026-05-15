# Gestor d'incidencies:
 - prjdam1-daw1-2025-26_hugo_alexb

# Integrant del grup:
 - Hugo Berea
 - Alexandre Brandao

# Breu descripció de l'aplicació
 - Aplicació web per obrir,organitzar i gestionar incidencies entre usuaris, técnics i administradors. 
 
# Enllaços d'interés
 - Adreça del taiga: https://tree.taiga.io/project/a25hugberbat-daw1pj3/timeline
 
 - Penpot: https://design.penpot.app/#/view?file-id=ceed1600-61c0-8087-8007-e98199991c63&page-id=ceed1600-61c0-8087-8007-e98199991c64&section=interactions&index=0&share-id=614162e1-9f0e-816a-8007-eead1924977b
 - URL de producció http://g3.daw.inspedralbes.cat/
 
 - GitHub: https://github.com/inspedralbes/projecte-1daw-25-26-daw1pj_hugo_alex-1

 - Historial de commits: https://github.com/inspedralbes/1daw-25-26-projecte-1daw-25-26-transversals_minim/compare/main...inspedralbes:projecte-1daw-25-26-daw1pj_hugo_alex-1:main

# WCAG AA
    - http://g3.daw.inspedralbes.cat/resources/Accessibilitat_Pc.html
    - http://g3.daw.inspedralbes.cat/resources/Accessibilitat_Telef.html


# 10 Heuristics


# Estat del projecte.
 - El projecte está finalitzat. Inclou un gestor d'incidències per els usuaris , tècnics i administradors.

# Instal·lació
    * Requisits necesaris
        - Docker
        - Docker compose

# Pasos
    1. Clonar el repositori:

    git clone https://github.com/inspedralbes/projecte-1daw-25-26-daw1pj_hugo_alex-1.git

    2. Crear el filcher '.env' a l'arrel del projcte:
        - dins del env trobarem:
            VAR1 = nom_usuari_BBDD
            VAR2 = contrasenya_BBDD
    
    3.  Arrancar els contenidors:
        - docker compose up
        - o doc docker compose up -d

    4.  Access a l'aplicació:
        - aplicació web: http://localhost:8080
        - adminer (MySQL): http://localhost:8081
        - MongoExpress: http://localhost:8082
