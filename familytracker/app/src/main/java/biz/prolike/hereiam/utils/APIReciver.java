package biz.prolike.hereiam.utils;


import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
public class APIReciver extends BroadcastReceiver {
    private Context mContext;
    private String TAG = "- API -";
    DatabaseHandler db;
    String g;
    @Override
    public void onReceive(Context context, Intent intent) {
        mContext = context;
        //Intent dailyUpdater = new Intent(context, SendApi.class);
        //context.startService(dailyUpdater);
        SendApi s = new SendApi(context);
        s.make();
    }

}
