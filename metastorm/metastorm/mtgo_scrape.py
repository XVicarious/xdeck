from lxml import html
import requests
import time

WIZARDS = "http://magic.wizards.com"
MTGO_LISTS = "/en/content/deck-lists-magic-online-products-game-info"

page = requests.get(WIZARDS + MTGO_LISTS)
tree = html.fromstring(page.content)

events = tree.xpath("//div[contains(@class,'article-item')]")
for event in events:
    url = WIZARDS + event[0].attrib['href']
    dates = event.xpath("//span[@class='date']")[0]
    month = dates[0].text.strip()
    date = str(dates[2].text).strip() + "-" + str(time.strptime(month.strip(), "%B").tm_mon) + "-" + str(dates[1].text).strip()
    event_page = requests.get(url)
    event_tree = html.fromstring(event_page.content)
    decks = event_tree.xpath("//div[@class='deck-group']")
    event_name = event_tree.xpath("//div[@id='main-content']/h1/text()")
    for deck in decks:
        deck_list = []
        brewer = deck.xpath("span[@class='deck-meta']/h4/text()")[0]
        deck_element = deck.xpath("div/div[@class='deck-list-text']/div[contains(@class, 'sorted-by-overview-container')]")[0]
        sideboard_element = deck.xpath("div/div[@class='deck-list-text']/div[contains(@class, 'sorted-by-sideboard-container')]")
        deck_subs = deck_element.xpath("div[contains(@class, 'element')]/span[@class='row']")
        for card in deck_subs:
            numberOf = card.xpath("span[@class='card-count']/text()")[0]
            cardName = card.xpath("span[@class='card-name']/a/text()")[0]
    break
