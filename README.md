helion_drupal_plugin
====================

Księgarnie GW Helion w Drupalu 7!!!

Wzbogać swoją stronę o ciekawe treści, w łatwy sposób stwórz własną, w pełni funkcjonalną księgarnię partnerską Grupy Wydawniczej Helion. Moduł Księgarni Internetowej w kilku prostych krokach pozwoli Ci na szybką implementację kopii wybranej z księgarni (Helion, Onepress, Sensus, Septem i Ebookpoint) na swojej stronie opartej o system CMS Drupal 7.

Katalog "helion_drupal_plugin" należy umieścić w ścieżce "sites/all/modules".

Moduł składa się z 4 części oraz sekcji administracyjnej:

Moduł główny księgarni

Dostęp do modułu następuje po podaniu w ścieżce adresu pozycji: http://{mojawitryna}/?q=ksiegarnia lub http://{mojawitryna}/ksiegarnia. Moduł ten zawiera dokładną kopię oferty wybranej księgarni (Helion, Onepress, Sensus, Septem i Ebookpoint) w formie prezentacji. Całość może być dowolnie zmieniana przez webmastera za pomocą arkuszy stylów.

Prezentacja wybranych elementów księgarni

Moduł definiuje 3 autonomiczne bloki, które można umieścić w dowolnym regionie systemu CMS Drupal:

Bestsellery - moduł wyświetla jedną z książek, które aktualnie znajdują się na liście bestsellerów wybranej przez ciebie księgarni.
Książka Dnia - codziennie w księgarniach Grupy Helion jedna książka jest dostępna w promocyjnej cenie uwzględniającej 20% lub 30% rabatu. Każdego dnia moduł wyświetla książkę objętą wspomnianą promocją w wybranej przez ciebie księgarni.
Polecana książka - ten moduł wyświetla zawsze jedną, wybraną przez ciebie książkę z dowolnej z księgarni Grupy Helion.

Sekcja administracyjna zawiera

Numer klienta programu partnerskiego (ID Partnera) - umożliwia podanie unikatowego numeru przypisanego na stałe do partnera. Każda pozycja przekierowana na adres księgarni zostanie opatrzona Twoim numerem ID.
Podstawowa księgarnia - ustawia podstawową księgarnię, która zostanie wyświetlona po załadowaniu modułu z adresu http://{mojawitryna}/?q=ksiegarnia lub http://{mojawitryna}/ksiegarnia. Ustawienie początkowe to księgarnia HELION.
Ilość książek wyświetlanych na stronie - pozwala na ustawienie ilości pozycji wyświetlanych na stronie. Początkowa wartość to 10 pozycji (10 książek).
Sposób wyświetlania - pozwala na ustawienie wyświetlania w księgarni całej oferty lub wyłącznie oferty eBooków.
Wyszukiwarka - uruchamia wyszukiwarkę pozycji w głównej księgarni.
Dodatkowe ustawienia - pozwalają na wyświetlenie listy wyboru księgarni (gdy opcja jest nieaktywna zostaną wyświetlone kategorie księgarni zdefiniowanej w sekcji Podstawowa księgarnia).
Kategorie opisu książek zawarte w widgetach (polecana książka, bestseller i książka dnia) są parametryzowane i pozwalają na wybór poszczególnych opcji (cena, tytuł, koszyk) pod daną pozycja.

Podstawowe ikony systemowe zawarte są w module. Istnieje również możliwość pobierania grafik z zewnętrznych źródeł.

Dodatkowo posiada system buforowania odwiedzanych stron a także możliwość wyświetlania książek tylko o danej tematyce.
