CREATE TABLE cset (
id TEXT PRIMARY KEY, 
name TEXT,
rls_dt TEXT,
created TEXT DEFAULT CURRENT_TIMESTAMP,
updated TEXT DEFAULT CURRENT_TIMESTAMP
);
create index cset1 on cset (id);
create index cset2 on cset (name);
/*create trigger setri after insert on cset begin
        update cset set start_dt = datetime('now','localtime','start of month') where id = new.id;
        end;
*/
create trigger csettr after update on cset begin
        update cset set updated = datetime('now','localtime') where id = new.id;
        end;
