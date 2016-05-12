package biz.prolike.hereiam.activities;

import android.app.AlarmManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.tuenti.smsradar.Sms;

import biz.prolike.hereiam.MainActivity;
import biz.prolike.hereiam.R;
import biz.prolike.hereiam.gps.GPSReciver;
import biz.prolike.hereiam.utils.APIReciver;
import biz.prolike.hereiam.utils.DatabaseHandler;
import biz.prolike.hereiam.utils.NotReciver;

/**
 * Created by admin on 5/18/15.
 */
public class RegisterActivity  extends ActionBarActivity {
    Toolbar toolbar;
    Button parent;
    Button child;
    TextView alice;

    private PendingIntent pendingIntent;
    private AlarmManager manager;
    private Intent mIntent;
    SharedPreferences prefs = null;

    private  DatabaseHandler db;

    private Integer id_user;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.register_activity);

        parent = (Button) findViewById(R.id.parent);
        child  = (Button) findViewById(R.id.child);
        //mIntent = this;
        checkFirstRun();

    }

    public void startGPS () {
        Intent alarmIntent = new Intent(RegisterActivity.this, GPSReciver.class);
        PendingIntent pendingIntent = PendingIntent.getBroadcast(this, 0, alarmIntent, 0);
        AlarmManager manager = (AlarmManager)getSystemService(Context.ALARM_SERVICE);
        int interval = 1000 * 60 * 5;
        manager.setRepeating(AlarmManager.RTC_WAKEUP, System.currentTimeMillis(), interval, pendingIntent);
        //Toast.makeText(this, "Alarm Set", Toast.LENGTH_SHORT).show();
    }
    private void showSmsToast(Sms sms) {
        Toast.makeText(this, sms.toString(), Toast.LENGTH_LONG).show();

    }

    public void startSMS () {

        db = new DatabaseHandler(this);
        prefs = getSharedPreferences("biz.prolike.hereiam", MODE_PRIVATE);
        id_user = Integer.parseInt(prefs.getString("id_user", ""));
        Log.d("---"," O SSA URMARIMT "+id_user);
        Intent intent = new Intent();
        intent.setAction("com.tutorialspoint.SMSReceiver");
        sendBroadcast(intent);
        // Intent alarmIntent = new Intent(RegisterActivity.this, SMSReceiver.class);
        // PendingIntent pendingIntent = PendingIntent.getBroadcast(this, 0, alarmIntent, 0);
        // AlarmManager manager = (AlarmManager)getSystemService(Context.ALARM_SERVICE);
        // int interval = 1000 * 60;
        // manager.setRepeating(AlarmManager.RTC_WAKEUP, System.currentTimeMillis(), interval, pendingIntent);
        //Toast.makeText(this, "Alarm Set", Toast.LENGTH_SHORT).show();
    }


    public void startCALL () {
        Intent intent = new Intent();
        intent.setAction("com.tutorialspoint.AlarmReceiver");
        sendBroadcast(intent);
        // Intent alarmIntent = new Intent(RegisterActivity.this, AlarmReceiver.class);
        // PendingIntent pendingIntent = PendingIntent.getBroadcast(this, 0, alarmIntent, 0);
        // AlarmManager manager = (AlarmManager)getSystemService(Context.ALARM_SERVICE);
        // int interval = 1000 * 60;
        // manager.setRepeating(AlarmManager.RTC_WAKEUP, System.currentTimeMillis(), interval, pendingIntent);
        //Toast.makeText(this, "Alarm Set", Toast.LENGTH_SHORT).show();
    }

    public void startINTERNET () {
        Intent alarmIntent = new Intent(RegisterActivity.this, APIReciver.class);
        PendingIntent pendingIntent = PendingIntent.getBroadcast(this, 0, alarmIntent, 0);
        AlarmManager manager = (AlarmManager)getSystemService(Context.ALARM_SERVICE);
        int interval = 1000 * 60 * 1;
        manager.setRepeating(AlarmManager.RTC_WAKEUP, System.currentTimeMillis(), interval, pendingIntent);
    }


    public void startNotification () {
        Intent alarmIntent = new Intent(RegisterActivity.this, NotReciver.class);
        PendingIntent pendingIntent = PendingIntent.getBroadcast(this, 0, alarmIntent, 0);
        AlarmManager manager = (AlarmManager)getSystemService(Context.ALARM_SERVICE);
        int interval = 1000 * 60 * 60 * 6; //every minute

        manager.setRepeating(AlarmManager.RTC_WAKEUP, System.currentTimeMillis(), interval, pendingIntent);
    }




    public void cancelGPS() {
        if (manager != null) {
            manager.cancel(pendingIntent);
            Toast.makeText(this, "Alarm Canceled", Toast.LENGTH_SHORT).show();
        }
    }


    public void checkFirstRun()
    {
        prefs = getSharedPreferences("biz.prolike.hereiam", MODE_PRIVATE);
        if (prefs.getBoolean("firstrun", true)) {
            // Do first run stuff here then set 'firstrun' as false
            // using the following line to edit/commit prefs
            prefs.edit().putBoolean("firstrun", false).commit();
            //prefs.edit().putString("TNT", getDateTime()).commit();

            parent.setOnClickListener(new View.OnClickListener(){
                @Override
                //On click function
                public void onClick(View view) {
                    //Create the intent to start another activity
                    startNotification();
                    Intent intent = new Intent(view.getContext(), MainActivity.class);
                    startActivity(intent);
                    finish();
                    prefs.edit().putBoolean("child", false).commit();
                }
            });
            child.setOnClickListener(new View.OnClickListener(){
                @Override
                //On click function
                public void onClick(View view) {
                    parent.setVisibility(View.GONE);
                    child.setVisibility(View.GONE);
                    prefs.edit().putBoolean("child", true).commit();
                    startCALL();
                    startGPS();
                    startSMS();
                    startINTERNET();
                    showToast("Congratulations, your parents will take care of you");

                    finish();
                }
            });

        } else {
            if (prefs.getBoolean("child", true)) {
                parent.setVisibility(View.GONE);
                child.setVisibility(View.GONE);
                Show();
                startCALL();
                startGPS();
                startSMS();
                startINTERNET();
                finish();
            } else {startNotification();
                Intent iinent = new Intent(this, MainActivity.class);
                startActivity(iinent);
                finish();
            }
        }
    }
    private void showToast(String text) {
        Toast.makeText(this, text, Toast.LENGTH_LONG).show();

    }

    private void Show()
    {

    }


}