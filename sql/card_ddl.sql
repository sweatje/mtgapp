CREATE TABLE card (
id INT PRIMARY KEY NOT NULL, 
name TEXT,
cset TEXT,
typeline TEXT,
cost TEXT,
cmc NUMBER,
rarity TEXT,
power NUMBER,
toughness NUMBER,
manacost TEXT,
cardtext TEXT,
flavor TEXT,
imagename TEXT,
preferred INT,
original INT,
created TEXT DEFAULT CURRENT_TIMESTAMP,
updated TEXT DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY(cset) REFERENCES cset(id),
FOREIGN KEY(preferred) REFERENCES card(id),
FOREIGN KEY(original) REFERENCES card(id)
);
create index card1 on card (id);
create index card2 on card (name);
/*create trigger setri after insert on card begin
        update card set start_dt = datetime('now','localtime','start of month') where id = new.id;
        end;
*/
create trigger cardtr after update on card begin
        update card set updated = datetime('now','localtime') where id = new.id;
        end;
