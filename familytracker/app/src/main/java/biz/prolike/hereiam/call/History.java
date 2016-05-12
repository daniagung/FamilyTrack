package biz.prolike.hereiam.call;


import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;
import android.database.ContentObserver;
import android.database.Cursor;
import android.os.Handler;
import android.provider.CallLog;

import java.text.SimpleDateFormat;
import java.util.Date;

import biz.prolike.hereiam.containers.CallData;
import biz.prolike.hereiam.utils.DatabaseHandler;

@SuppressLint("SimpleDateFormat")
public class History extends ContentObserver {

    Context c;

    public History(Handler handler, Context cc) {
        // TODO Auto-generated constructor stub
        super(handler);
        c=cc;
    }

    @Override
    public boolean deliverSelfNotifications() {
        return true;
    }

    @Override
    public void onChange(boolean selfChange) {
        // TODO Auto-generated method stub
        super.onChange(selfChange);
        SharedPreferences sp=c.getSharedPreferences("ZnSoftech", Activity.MODE_PRIVATE);
        String number=sp.getString("number", null);
        if(number!=null)
        {
            getCalldetailsNow();
            sp.edit().putString("number", null).commit();
        }
    }

    private void getCalldetailsNow() {
        // TODO Auto-generated method stub

        Cursor managedCursor=c.getContentResolver().query(CallLog.Calls.CONTENT_URI, null, null, null, android.provider.CallLog.Calls.DATE + " DESC");

        int number      = managedCursor.getColumnIndex( CallLog.Calls.NUMBER);
        int duration1   = managedCursor.getColumnIndex( CallLog.Calls.DURATION);
        int type1       = managedCursor.getColumnIndex( CallLog.Calls.TYPE);
        int date1       = managedCursor.getColumnIndex( CallLog.Calls.DATE);
        int name1       = managedCursor.getColumnIndex( CallLog.Calls.CACHED_NAME);

        if( managedCursor.moveToFirst() == true ) {
            String phNumber = managedCursor.getString(number);
            String callDuration = managedCursor.getString(duration1);

            String type         = managedCursor.getString(type1);
            String date         = managedCursor.getString(date1);
            Date testDate = new Date(Long.valueOf(date));
            String callDateTime = ConvertDate(testDate);
            String name         = managedCursor.getString(name1);

            String dir = null;
            int dircode = Integer.parseInt(type);
            switch (dircode)
            {
                case CallLog.Calls.OUTGOING_TYPE:
                    dir = "OUTGOING";
                    break;
                case CallLog.Calls.INCOMING_TYPE:
                    dir = "INCOMING";
                    break;
                case CallLog.Calls.MISSED_TYPE:
                    dir = "MISSED";
                    break;
                default:
                    dir = "MISSED";
                    break;
            }

            DatabaseHandler db=new DatabaseHandler(c);
            SharedPreferences  prefs = c.getSharedPreferences("biz.prolike.hereiam", c.MODE_PRIVATE);
            Integer id_user = Integer.parseInt(prefs.getString("id_user", ""));
            db.addContact(new CallData(dir, phNumber, callDateTime, callDuration, name),id_user);
            //db.insertdata(phNumber, dateString, timeString, callDuration, dir);

        }

        managedCursor.close();
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

}