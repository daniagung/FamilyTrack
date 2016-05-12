package biz.prolike.hereiam.gps;


import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;

import biz.prolike.hereiam.activities.RegisterActivity;

public class GpsTrackerBootReceiver extends BroadcastReceiver {
    private static final String TAG = "GpsTrackerBootReceiver";
    private static  Context mContext;
    @Override
    public void onReceive(Context context, Intent intent) {
        mContext = context;
        Intent i = new Intent(context, RegisterActivity.class);
        i.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        context.startActivity(i);
    }

}