  * PHP 7.1.3 or higher;
  * PDO-SQLite PHP extension enabled;
  * MySql 5.7
  
 Przy pomocy aplikacji będzie można:
- korzystać, obstawiać mecze bundesligi i zbierać punkty za obstawienia - tablica wyników użytkowników
- mecze i wyniki pobierane przy pomocy REST API (FootballData)
- tworzyć wydarzenia sportowe(publiczne, bądź prywatne), zapraszać znajomych do tych wydarzeń, wyszukiwać wydarzenia oraz można będzie  dołączać do wydarzeń
- komentować wydarzenia
- lista moich wydarzeń
- sprawdzanie przesyłek Poczty Polskiej (SOAP API)

Co zrobione?
- Komenda do pobierania meczy ligi niemieckiej do bazy danych z footballdata (rest)
- Komenda do aktualizowania w bazie wyników zakończonych spotkań
- Obstawianie spotkań wg. kolejek począwczy od najbliższej możliwej
- Funkcja naliczająca zalogowanemu użytkownikowi punkty za jego obstawienia
- Ranking użytkowników wg punktów
- Historia zakończonych typów - spotkanie, obstawienie i ile punktów otrzymał użytkownik
- Tworzenie wydarzen
- Wyszukiwanie wydarzen
- dolaczanie do wydarzenia
- Zmniejsza się ilosc miejsc w danym wydarzeniu, gdy ktoś dołączy
- Nie mozna sie zapisac do wydarzenia w ktorym sie bierze juz udzial
- Sprawdzanie wysylek z poczty Polskiej (soap)
- lista wydarzeń w których bierze się udział,
- lista wydarzeń w których się bralo udział

Co do zrobienia?
- uprawnienia użytkowniów, nie wszędzie są
- walidacja formularzy
- baza miast i wyszukwianie wydarzeń po miastach wczytywanych z bazy
- wygląd aplikacji
- zapraszanie na wydarzenia
- obstawianie i zmiana obstawienia w typerze do 4 godzin przed meczem

