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

1.Visibilitat de l'estat del sistema (Visibility of System Status): 

 -Quan l'usuari envia un formulari, rep un missatge clar de "S'ha enviat correctament", quan elimina, canvia o edita qualsevol acció o incidencia també surt un missatge indicant-ho.

2.Correspondència entre el sistema i el món real (Match Between System and Real World): 

 -Els errors estan ben escrits i els icones estan ben escollits i intuïtius.

3.Control i llibertat de l'usuari (User Control and Freedom):

 -Si es tanca una incidencia sense voler, el admin pot obrir-la.
 -Si vol eliminar o tancar alguna incidencia, surt un missatge de confirmació.
 -Hi han botos per tornar a l'inici i cap enrere

4.Consistència i estàndards (Consistency and Standards):

 -Tota la web manté una harmonia de colors i estils, els botons importants estan sempre en les mateixes zones

5.Prevenció d'errors (Error Prevention):

 -Els camps de dades ofereixen un calendari desplegable, els camps de descripció estan capats amb número de caràcters.
 -Si falten camps obligatoris surt un error indicant-lo.
 -Si vol eliminar o tancar alguna incidencia, surt un missatge de confirmació.

6.Reconeixement millor que record (Recognition Rather Than Recall):

 -S’indica en tot moment on està l’usuari i com tornar enrere o a l’inici.
 -Quan es crea una incidència o acció, es dirigeix directament a la llista corresponent.

7.Flexibilitat i eficiència d'ús (Flexibility and Efficiency of Use):

 -Els usuaris poden utilitzar dreceres de teclat o el botó "Tab" per passar d'un camp a un altre d'un formulari
 -Hi ha un buscador d'incidències per id.

8.Disseny estètic i minimalista Aesthetic and Minimalist Design:

 -No hi ha text innecessari o paràgrafs massa llargs.
 -Visualment, està clar quin és l'element més important de la pàgina
 -Hi ha prou espai en blanc perquè la vista de l'usuari pugui "respirar"

9.Ajudar els usuaris a reconèixer, diagnosticar i recuperar-se d'errors (Help Users Recognize, Diagnose, and Recover from Errors):

 -Quan hi ha un error s’indica quin tipus és i si manca algun camp també ho posa.

10.Ajuda i documentació (Help and Documentation):

 -Hi ha un filtre en totes les llistes i els tècnics només poden veure les seves incidències assignades

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
