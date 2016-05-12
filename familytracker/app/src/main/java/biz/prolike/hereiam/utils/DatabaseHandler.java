package biz.prolike.hereiam.utils;


import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.util.Log;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import biz.prolike.hereiam.containers.CallData;
import biz.prolike.hereiam.containers.ChildData;
import biz.prolike.hereiam.containers.LocData;
import biz.prolike.hereiam.containers.SMSData;


public class DatabaseHandler extends SQLiteOpenHelper {

    // All Static variables
    // Database Version
    private static final int DATABASE_VERSION = 7;

    // Database Name
    private static final String DATABASE_NAME = "hereIam";

    // Contacts table name
    private static final String TABLE_CONTACTS = "contacts";
    private static final String TABLE_MESSAGES = "messages";
    private static final String TABLE_POSITION = "position";

    private static final String TABLE_USER = "user";
    private static final String TABLE_CHILDS = "childs";
    private static final String TABLE_UPS = "ups";


    public DatabaseHandler(Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    // Creating Tables
    @Override
    public void onCreate(SQLiteDatabase db) {
        String CREATE_CONTACTS_TABLE = "CREATE TABLE " + TABLE_CONTACTS + "("
                + "id INTEGER PRIMARY KEY, number TEXT,"
                + "name TEXT, duration TEXT, id_user INTEGER, type INTEGER, new INTERGET, date TEXT" + ")";
        db.execSQL(CREATE_CONTACTS_TABLE);

        String CREATE_MESSAGES_TABLE = "CREATE TABLE " + TABLE_MESSAGES + "("
                + "id INTEGER PRIMARY KEY, number TEXT,"
                + "body TEXT,  id_user INTEGER, type INTEGER, new INTERGET, date DATE" + ")";
        db.execSQL(CREATE_MESSAGES_TABLE);

        String CREATE_POSITION_TABLE = "CREATE TABLE " + TABLE_POSITION + "("
                + "id INTEGER PRIMARY KEY, lon TEXT,"
                + "lat TEXT, id_user INTEGER, new INTERGET, date date" + ")";
        db.execSQL(CREATE_POSITION_TABLE);

        String CREATE_USER_TABLE = "CREATE TABLE " + TABLE_USER + "("
                + "id INTEGER PRIMARY KEY, name TEXT,"
                + "email TEXT, photo TEXT, id_user INTEGER, type INTEGER, birthday DATE" + ")";
        db.execSQL(CREATE_USER_TABLE);

        String CREATE_CHILDS_TABLE = "CREATE TABLE " + TABLE_CHILDS + "("
                + "id INTEGER PRIMARY KEY, name TEXT,"
                + "email TEXT, photo TEXT, id_user INTEGER, birdthday DATE" + ")";
        db.execSQL(CREATE_CHILDS_TABLE);

        db.execSQL("CREATE TABLE " + TABLE_UPS + "(id INTEGER PRIMARY KEY, date date)");


    }

    // Upgrading database
    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        // Drop older table if existed
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_CONTACTS);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_MESSAGES);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_POSITION);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_USER);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_CHILDS);
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_UPS);

        // Create tables again
        onCreate(db);
    }

    public void addUPS(String data)
    {
        SQLiteDatabase db = this.getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put("date", data); //Date
        db.insert(TABLE_UPS, null, values);
        db.close(); // Closing database connection
    }


    /**
     * All CRUD(Create, Read, Update, Delete) Operations
     */
    public void addChild(Integer id_user, String name, String email,String photo)
    {
        SQLiteDatabase db = this.getWritableDatabase();

        ContentValues values = new ContentValues();
        values.put("id_user", id_user); // GPS Latitude
        values.put("name", name); // GPS Longitude
        values.put("email", email); //GPS Date
        values.put("photo", photo); // GPS ID
        // Inserting Row
        db.insert(TABLE_CHILDS, null, values);
        db.close(); // Closing database connection
    }

    //Adding new GPS Location
    public void addUser(Integer id_user, String name, String email,String photo, String type, String birthday)
    {
        SQLiteDatabase db = this.getWritableDatabase();

        ContentValues values = new ContentValues();
        values.put("id_user", id_user); // GPS Latitude
        values.put("name", name); // GPS Longitude
        values.put("email", email); //GPS Date
        values.put("birthday", birthday); // GPS ID
        values.put("photo", photo); // GPS ID
        values.put("type", type); // GPS ID
        // Inserting Row
        db.insert(TABLE_USER, null, values);
        db.close(); // Closing database connection
    }

    public boolean checkUser(String email)
    {
        boolean string = false;
        SQLiteDatabase db = this.getReadableDatabase();

        String selectQuery = "SELECT  * FROM " + TABLE_USER + " WHERE email = '"+email+"'";

        Cursor c = db.rawQuery(selectQuery, null);
        if (c != null)
            string = true;

        return string;
    }

    public String getUserId()
    {
        String string = "";

        String selectQuery = "SELECT  * FROM " + TABLE_USER + " LIMIT 1";
        SQLiteDatabase db = this.getWritableDatabase();
        Cursor c = db.rawQuery(selectQuery, null);

        if (c.moveToFirst()) {
            string = ""+c.getString(4);
        }


        return string;
    }

    //Adding new GPS Location
    public void addGPS(String lot, String lon,String date,Integer id_user)
    {
        SQLiteDatabase db = this.getWritableDatabase();
        Log.d(" 00", date);
        ContentValues values = new ContentValues();
        values.put("lat",       lot); // GPS Latitude
        values.put("lon",      lon); // GPS Longitude
        values.put("date",      date); //GPS Date
        values.put("id_user",    id_user); // GPS ID User
        values.put("new",       "1");
        // Inserting Row
        db.insert(TABLE_POSITION, null, values);
        db.close(); // Closing database connection
    }



    //Adding new sms
    public void addSMS(String number, String body, String type, String date,Integer id_user)
    {
        SQLiteDatabase db = this.getWritableDatabase();

        ContentValues values = new ContentValues();
        values.put("number",    number); // SMS Number
        values.put("body",      body); // SMS Body
        values.put("type", type); // SMS Type
        values.put("date", date); //SMS Date
        values.put("id_user", id_user); // SMS ID User
        values.put("new",       "1");
        // Inserting Row
        db.insert(TABLE_MESSAGES, null, values);
        db.close(); // Closing database connection
    }

    // Getting All Contacts
    public List<SMSData> getAllCsms() {
        List<SMSData> contactList = new ArrayList<SMSData>();
        // Select All Query
        String selectQuery = "SELECT  * FROM " + TABLE_MESSAGES + " ORDER BY date DESC";

        SQLiteDatabase db = this.getWritableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);

        // looping through all rows and adding to list
        if (cursor.moveToFirst()) {
            do {
                SMSData sms = new SMSData();
                sms.setNumber(cursor.getString(1));
                sms.setBody(cursor.getString(2));
                sms.setType(cursor.getString(4));
                sms.setDate(cursor.getString(5));
                // Adding contact to list
                contactList.add(sms);
            } while (cursor.moveToNext());
        }

        // return contact list
        return contactList;
    }


    // Adding new contact
    public void addContact(CallData contact, Integer id_user) {
        SQLiteDatabase db = this.getWritableDatabase();

        ContentValues values = new ContentValues();
        values.put("number",    contact.getCallnumber()); // Contact Number
        values.put("name",      contact.getCallname()); // Contact Name
        values.put("duration",  secondsToString(Integer.parseInt(contact.getCallduration()))); // Contact Duration
        values.put("type",      contact.getCalltype()); // Contact Type
        values.put("id_user",   id_user); // Contact ID User
        values.put("date",       contact.getCalldatetime()); // Contact Date
        values.put("new",       "1");
        // Inserting Row
        db.insert(TABLE_CONTACTS, null, values);
        db.close(); // Closing database connection
    }
    // Getting All Contacts
    public List<ChildData> getAllChilds() {
        List<ChildData> contactList = new ArrayList<ChildData>();
        // Select All Query
        String selectQuery = "SELECT  * FROM " + TABLE_CHILDS + " ORDER BY id DESC";

        SQLiteDatabase db = this.getWritableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);

        // looping through all rows and adding to list
        if (cursor.moveToFirst()) {
            do {
                ChildData contact = new ChildData();
                contact.setEmail(cursor.getString(2));
                contact.setName(cursor.getString(1));
                contact.setPhoto(cursor.getString(3));
                contact.setId(cursor.getString(4));
                // Adding contact to list
                contactList.add(contact);
            } while (cursor.moveToNext());
        }

        // return contact list
        return contactList;
    }

    // Getting All Contacts
    public List<CallData> getAllContacts() {
        List<CallData> contactList = new ArrayList<CallData>();
        // Select All Query
        String selectQuery = "SELECT  * FROM " + TABLE_CONTACTS + " ORDER BY date DESC";

        SQLiteDatabase db = this.getWritableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);

        // looping through all rows and adding to list
        if (cursor.moveToFirst()) {
            do {
                CallData contact = new CallData();
                contact.setCallnumber(cursor.getString(1));
                contact.setCallname(cursor.getString(2));
                contact.setCallduration(cursor.getString(3));
                contact.setCalltype(cursor.getString(5));
                contact.setCalldatetime(cursor.getString(7));
                // Adding contact to list
                contactList.add(contact);
            } while (cursor.moveToNext());
        }

        // return contact list
        return contactList;
    }


    // Getting All Contacts
    public List<CallData> getCallstoPost() {
        List<CallData> contactList = new ArrayList<CallData>();
        // Select All Query
        String selectQuery = "SELECT  * FROM " + TABLE_CONTACTS + " WHERE new = '1'  ORDER BY date DESC";

        SQLiteDatabase db = this.getWritableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);

        // looping through all rows and adding to list
        if (cursor.moveToFirst()) {
            do {
                CallData contact = new CallData();
                contact.setCallnumber(cursor.getString(1));
                contact.setCallname(cursor.getString(2));
                contact.setCallduration(cursor.getString(3));
                contact.setCalltype(cursor.getString(5));
                contact.setCalldatetime(cursor.getString(7));
                // Adding contact to list
                contactList.add(contact);
            } while (cursor.moveToNext());
        }

        // return contact list
        return contactList;
    }

    public List<SMSData> getSMStoPost() {
        List<SMSData> contactList = new ArrayList<SMSData>();
        // Select All Query
        String selectQuery = "SELECT  * FROM " + TABLE_MESSAGES + " WHERE new = '1' ORDER BY date DESC";

        SQLiteDatabase db = this.getWritableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);

        // looping through all rows and adding to list
        if (cursor.moveToFirst()) {
            do {
                SMSData sms = new SMSData();
                sms.setNumber(cursor.getString(1));
                sms.setBody(cursor.getString(2));
                sms.setType(cursor.getString(4));
                sms.setDate(cursor.getString(6));
                // Adding contact to list
                contactList.add(sms);
            } while (cursor.moveToNext());
        }

        // return contact list
        return contactList;
    }

    public List<LocData> getLoctoPost() {
        List<LocData> contactList = new ArrayList<LocData>();
        // Select All Query
        String selectQuery = "SELECT  * FROM " + TABLE_POSITION + " WHERE new = '1' ORDER BY date DESC";

        SQLiteDatabase db = this.getWritableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);

        // looping through all rows and adding to list
        if (cursor.moveToFirst()) {
            do {
                LocData loc = new LocData();
                loc.setLat(cursor.getString(2));
                loc.setLon(cursor.getString(1));
                loc.setDate(cursor.getString(5));
                // Adding contact to list
                contactList.add(loc);
            } while (cursor.moveToNext());
        }

        // return contact list
        return contactList;
    }


    public void reset(String table)
    {   SQLiteDatabase db = this.getReadableDatabase();
        db.delete(table,null,null);
        db.close();
    }



    public void removeChild(String email)
    {
        String countQuery = "DELETE FROM " + TABLE_CHILDS+" WHERE email = '"+email+"'";
        SQLiteDatabase db = this.getReadableDatabase();
        db.execSQL("delete from " + TABLE_CHILDS + " where email='" + email + "'");
        Cursor cursor = db.rawQuery(countQuery, null);
        Log.d("---", " ---" + email);
    }

    public String getChild(String id,String field)
    {
        String ret = "";
        String selectQuery = "SELECT  * FROM " + TABLE_CHILDS + " WHERE id_user = '"+ id +"' LIMIT 1";
        Log.d("-MEGA QUERY", selectQuery);
        SQLiteDatabase db = this.getWritableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);

        // looping through all rows and adding to list
        if (cursor.moveToFirst()) {
                if(field.equals("email")) {
                        ret = cursor.getString(1);
                    } else if(field.equals("name")) {
                        ret = cursor.getString(2);
                    } else if(field.equals("photo")) {
                        ret = cursor.getString(3);
                    }
        }

        // return contact list
        return ret;

    }

    public int getCount(String table)
    {
        String countQuery = "SELECT  * FROM " + table;
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.rawQuery(countQuery, null);
        // return count
        return cursor.getCount();
    }
    public int getChildCount() {
        String countQuery = "SELECT  * FROM " + TABLE_CHILDS;
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.rawQuery(countQuery, null);
        // return count
        return cursor.getCount();
    }

    public static String ConvertDate(Date date) {
        String dateForMySql = "";
        if (date == null) {
            dateForMySql = null;
        } else {
            SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
            dateForMySql = sdf.format(date);
        }

        return dateForMySql;
    }

    private String secondsToString(int pTime) {
        return String.format("%02d:%02d", pTime / 60, pTime % 60);
    }
    private String getDateTime() {
        DateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        Date date = new Date(); return dateFormat.format(date);
    }
    public void closeDB() {
        SQLiteDatabase db = this.getReadableDatabase();
        if (db != null && db.isOpen())
            db.close();
    }
}