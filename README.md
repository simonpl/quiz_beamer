Quiz_beamer
===========

(English version will follow)

Vorbereitung
------------

Um einen Quizabend vorzubereiten, denke dir zunächst zu vier Themengebieten jeweils fünf Fragen in steigender Schwierigkeit aus. Die Fragen können dabei folgendes sein:

    * Multiple-Choice-Fragen mit vier Antwortmöglichkeiten
    * Schätzfragen mit einer richtigen Antwort und einer Toleranz, mit welcher die Frage als richtig gewertet wird.
    * Schnellfragerunden, bei denen auf dem Beamer keine Fragen erscheinen, sondern hinterher nur die Anzahl der richtig beantworteten Fragen eingegeben wird.
    * Sortierfragen, bei denen auf dem Beamer mehrere Dinge erscheinen und eine Sortieraufgabe zu diesen.
    * Aktionen, bei denen vorne auf dem Beamer nur eine Aufgabenstellung erscheint.

1. Kopiere die Dateien `quiz.php`, `settings.php.dist` sowie die Ordner `bootstrap` und `lang` mitsamt Inhalt in ein Verzeichnis, welches von deinem Webserver erreicht werden kann.

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
