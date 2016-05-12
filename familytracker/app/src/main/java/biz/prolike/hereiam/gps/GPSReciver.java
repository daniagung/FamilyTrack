package biz.prolike.hereiam.gps;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.widget.Toast;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

import biz.prolike.hereiam.utils.DatabaseHandler;

public class GPSReciver extends BroadcastReceiver {
    private static final String TAG = "GPSReciver";
    private static Context mContext;

    @Override
    public void onReceive(Context context, Intent intent) {
        mContext = context;
        LocationTracker gps = new LocationTracker(context);
        DateFormat dateFormatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        dateFormatter.setLenient(false);
        Date today = new Date();
        String datemess = dateFormatter.format(today);
        DatabaseHandler db = new DatabaseHandler(context);
        SharedPreferences prefs = context.getSharedPreferences("biz.prolike.hereiam", context.MODE_PRIVATE);
        Integer id_user = Integer.parseInt(prefs.getString("id_user", ""));
        Double lat = gps.getLatitude();
        Double lon = gps.getLongitude();
        if (!lat.equals("0.0") && !lon.equals("0.0")) {
            db.addGPS("" + lat, "" + lon, datemess, id_user);
            //showSmsToast(lat + "    " + lon);
        }
    }

    public void showSmsToast(String text) {
        Toast.makeText(mContext,text,
                Toast.LENGTH_LONG).show();
    }
}