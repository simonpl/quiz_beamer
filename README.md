Quiz_beamer
===========

(English version below)

Vorbereitung
------------

Um einen Quizabend vorzubereiten, denke dir zunächst zu vier Themengebieten jeweils fünf Fragen in steigender Schwierigkeit aus. Die Fragen können dabei folgendes sein:

    * Multiple-Choice-Fragen mit vier Antwortmöglichkeiten
    * Schätzfragen mit einer richtigen Antwort und einer Toleranz, mit welcher die Frage als richtig gewertet wird.
    * Schnellfragerunden, bei denen auf dem Beamer keine Fragen erscheinen, sondern hinterher nur die Anzahl der richtig beantworteten Fragen eingegeben wird.
    * Sortierfragen, bei denen auf dem Beamer mehrere Dinge erscheinen und eine Sortieraufgabe zu diesen.
    * Aktionen, bei denen vorne auf dem Beamer nur eine Aufgabenstellung erscheint.

1. Kopiere die Dateien `quiz.php` und `settings.php.dist` sowie die Ordner `bootstrap` und `lang` mitsamt Inhalt in ein Verzeichnis, welches von deinem Webserver erreicht werden kann.

2. Bennene die Datei `settings.php.dist` in `settings.php` um.

3. Passe in der Datei `settings.php` die Werte an.

    3.1 Die Werte für die Datenbank sollten selbsterklärend sein. Lege für quiz_beamer am besten eine eigene Datenbank mit einem eigenen Benutzer an.
    
    3.2 QUIZ_BROWSER_TITLE enthält, was später in der Titelzeile vom Browser angezeit wird, sofern dieser nicht im Vollbildmodus läuft.
    
    3.3 QUIZ_CATEGORY_1 bis QUIZ_CATEGORY_4 enthalten die vier Überschriften zu den Themengebieten.
    
    3.4 QUIZ_LANGUAGE enthält die Sprache der Menüführung. In diesem Fall meist `de` für Deutsch, es ist allerdings auch `en` für Englisch verfügbar. In weitere Sprachen wurde quiz_beamer noch nicht übersetzt.
    
4. Führe die Datei `init.sql` in der Datenbank aus, die auch in `settings.php` spezifiziert wurde.

5. Pflege die Fragen in die Datenbanken ein. Den Feldern sind dabei folgende Fragen-ID's zugeordnet:

```
---------------------------------------------------------
| Kategorie 1 | Kategorie 2 | Kategorie 3 | Kategorie 4 |
---------------------------------------------------------
| Frage 1     | Frage 2     | Frage 3     | Frage 4     |
| (20 Punkte) | (20 Punkte) | (20 Punkte) | (20 Punkte) |
---------------------------------------------------------
| Frage 5     | Frage 6     | Frage 7     | Frage 8     |
| (40 Punkte) | (40 Punkte) | (40 Punkte) | (40 Punkte) |
---------------------------------------------------------
| Frage 9     | Frage 10    | Frage 11    | Frage 12    |
| (60 Punkte) | (60 Punkte) | (60 Punkte) | (60 Punkte) |
---------------------------------------------------------
| Frage 13    | Frage 14    | Frage 15    | Frage 16    |
| (80 Punkte) | (80 Punkte) | (80 Punkte) | (80 Punkte) |
---------------------------------------------------------
| Frage 17    | Frage 18    | Frage 19    | Frage 20    |
| (100 Punkte)| (100 Punkte)| (100 Punkte)| (100 Punkte)|
---------------------------------------------------------
```

Die einzelnen Fragetypen sollten dabei wie folgt eingepflegt werden (id und used unangetastet lassen, die übrigen nicht spezifizierten Felder sollten jeweils NULL sein)

    * Multiple-Choice-Frage: 
        * type: 'question'
        * text: Die Fragestellung
        * answer_a bis answer_d: Die vier Antwortmöglichkeiten
        * correct: Die richtige Antwort, 'A', 'B', 'C' oder 'D'
    * Schätzfragen:
        * type: 'estimate'
        * text: Die Fragestellung
        * correct: Die richtige Antwort in Textform, z.B. '30,2 Sekunden'
        * correct_value: Die richtige Antwort als Zahl, z.B. 30.2 (Wichtig: . und nicht , als Dezimaltrennzeichen)
        * marge: Die Toleranz in Textform, z.B. '0,4 Sekunden'
        * marge_value: Die Toleranz als Zahl, z.B. 0.4 (Wichtig: . und nicht , als Dezimaltrennzeichen)
    * Schnellfragerunde
        * type: 'fast'
        * text: Eine Überschrift, z.B. 'Schnellraterunde!'
    * Sortierfrage
        * type: 'sort'
        * text: Die Sortierfragestellung
        * text2: Die zu sortierenden Dinge in folgender Form: `<ul><li><h3 style="margin:0px">Item 1</h3></li><li><h3 style="margin:0px">Item 2</h3></li></ul>`
    * Aktion
        * type: 'action'
        * text: Eine beschreibung der Aktion

Durchführung
------------

Zum durchführen des Quizabends im Browser `quiz.php` aufrufen. Wenn z.B. nach einer Proberunde, alles wieder auf Anfang gesetzt werden soll: `quiz.php?action=resetall` und anschließend wieder `quiz.php`.

Das eigentliche Spiel läuft wie folgt ab: Jede Gruppe darf, beginnend bei Gruppe 1, reihum eine Frage auswählen und muss diese beantworten. Wenn die Frage richtig beantwortet wurde, werden normalerweise die Punkte der ausgewählten Frage auf das Konto der jeweiligen Gruppe gutgeschrieben, ansonsten nicht. Es gibt die beiden folgenden Ausnahmen:

* Bei einer Schnellfragerunde gibt es nicht unbeding so viele Punkte wie die Frage wert ist, sondern für jede beantwortete Frage 20 Punkte.
 
* Bei einer Aktion werden erst einmal gar keine Punkte gutgeschrieben, da es hier auch möglich ist, das ein seperates Spiel gespielt wird oder ähnliches, bei dem entweder eine andere Gruppe Punkte bekommt oder mehrere Gruppen Punkte bekommen. In diesem Fall müssen die vergebenen Punkte manuell in die Datenbank eingepflegt werden.

Lizenz
------

Alle Dateien außerhalb des Ordners `bootstrap`:

```
Copyright (C) 2013 Simon Plasger

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

See COPYING for details.
```

Der Ordner `bootstrap`:

Siehe https://github.com/twbs/bootstrap/blob/master/LICENSE

English Version
---------------


Preparation
------------

To prepare a quiz evening, think of 20 questions in four categories (five questions with rising difficulty in each category). The questions have to be one of the following:

    * Multiple-choice-questions with four possible answers
    * Questions where you have to guess the answer and have a tolerance, with wich the answer is assumed correct.
    * Fast questioning, where no questions appear at the beamer and only the number of correct answers has to be inserted
    * Sorting questions, where multiple items are to be sorted by the players.
    * Actions, only the task will appear.

1. Copy the files `quiz.php` and `settings.php.dist` and the folders `bootstrap` and `lang` with its contents to a direcory that can be accessed by your webserver.

2. Rename `settings.php.dist` to `settings.php`.

3. Adjust `settings.php`.

    3.1 The values for the Database should be self explaining. If you want, you can create a new database and a user that can only access this database for quiz_beamer.
    
    3.2 QUIZ_BROWSER_TITLE containts the title that will be shown in the Browser title bar.
    
    3.3 QUIZ_CATEGORY_1 to QUIZ_CATEGORY_4 contain the headlines for the four categories.
    
    3.4 QUIZ_LANGUAGE contains the language that will be used by quiz_beamer. In this case you may want to use 'en' for English. It is also possible to use 'de' for german. Currently quiz_beamer has not been translated to other languages.
    
4. Execute `init.sql` in the database you specified in `settings.php`

5. Fill the database with the questions. The fields on the beamer are connected to the following question ids.

```
---------------------------------------------------------
| Category 1  | Category 2  | Categorie 3 | Category 4  |
---------------------------------------------------------
| Question 1  | Question 2  | Question 3  | Question 4  |
| (20 points) | (20 points) | (20 points) | (20 points) |
---------------------------------------------------------
| Question 5  | Question 6  | Question 7  | Question 8  |
| (40 points) | (40 points) | (40 points) | (40 points) |
---------------------------------------------------------
| Question 9  | Question 10 | Question 11 | Question 12 |
| (60 points) | (60 points) | (60 points) | (60 points) |
---------------------------------------------------------
| Question 13 | Question 14 | Question 15 | Question 16 |
| (80 points) | (80 points) | (80 points) | (80 points) |
---------------------------------------------------------
| Question 17 | Question 18 | Question 19 | Question 20 |
| (100 points)| (100 points)| (100 points)| (100 points)|
---------------------------------------------------------
```

The questions have to be inserted like this: (don't touch `id` and `used`, the other fields not specified here should be set to `NULL`)

    * Multiple-choice-question:
        * type: 'question'
        * text: The question
        * answer_a bis answer_d: The four answers
        * correct: The correct answer, 'A', 'B', 'C' or 'D'
    * Questions where the players have to guess:
        * type: 'estimate'
        * text: The question
        * correct: The correct answer as text, i.e. '30.2 seconds'
        * correct_value: The correct answer as number, i.e. 30.2 (Important: Use . as decimal mark (don't use ,))
        * marge: The tolerance as text, i.e. '0.4 seconds'
        * marge_value: The tolerance as number, i.e. 0.4 (Important: Use . as decimal mark (don't use ,))
    * Fast questioning
        * type: 'fast'
        * text: A title, i.e. 'Answer fast!'
    * Sortierfrage
        * type: 'sort'
        * text: The sort questions
        * text2: The items that have to be sorted, noted like this: `<ul><li><h3 style="margin:0px">Item 1</h3></li><li><h3 style="margin:0px">Item 2</h3></li></ul>`
    * Aktion
        * type: 'action'
        * text: A description of the action

Performance
-----------

To perform the quiz evening, open `quiz.php` in your browser. If you want to reset the points and the used questions, i.e. after a test of your questions, open `quiz.php?action=resetall` and then `quiz.php` again.

The game will be played like this: Each group in turn (beginning with group 1) may choose a question and then has to answer it. If the question has been answered correct, the group will get the points that are connected to this questions, otherwise it will get nothing. There are the following exceptions:

* At fast questioning, the group doesn't get the points that are connected to the field that has been chosen. Instead of this, there will be 20 points for every question that has been answered correct.
 
* At actions there are no points saved for no group so that you can play a seperate game where another group or multiple groups can get points. These results then have to be inserted into the database manually.

License
------

All files that are not in `bootstrap`:

```
Copyright (C) 2013 Simon Plasger

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

See COPYING for details.
```

The files in `bootstrap`:

See https://github.com/twbs/bootstrap/blob/master/LICENSE
