package biz.prolike.hereiam.adapters;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.NetworkImageView;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

import biz.prolike.hereiam.AppController;
import biz.prolike.hereiam.R;
import biz.prolike.hereiam.containers.LocData;

/**
 * Created by admin on 5/20/15.
 */
public class PlaceAdapter extends ArrayAdapter<LocData> {

    private List<LocData> listdata=null;
    private LayoutInflater mInflater=null;
    private Activity mContext;
    ImageLoader imageLoader = AppController.getInstance().getImageLoader();
    public PlaceAdapter(Activity context, List<LocData> calldata) {
        super(context, 0);
        this.listdata=calldata;
        mInflater = context.getLayoutInflater();
        this.mContext = context;
    }


    @Override
    public int getCount() {
        return listdata.size();
    }


    public View getView(int position, View convertView, ViewGroup parent) {

        final ViewHolder holder;

        if (imageLoader == null)
            imageLoader = AppController.getInstance().getImageLoader();
        if (convertView == null || convertView.getTag() == null) {
            holder = new ViewHolder();
            convertView = mInflater.inflate(R.layout.location_item, null);

            holder.street = (TextView) convertView.findViewById(R.id.street);
            holder.data = (TextView) convertView.findViewById(R.id.data);
            holder.image = (NetworkImageView) convertView.findViewById(R.id.thumbnail);
            convertView.setTag(holder);
        }
        else {
            holder = (ViewHolder) convertView.getTag();
        }

        LocData loc = listdata.get(position);
        String lat =loc.getLat();
        String lon = loc.getLon();
        String data = loc.getDate();
        String address = loc.getAddress();
        holder.image.setImageUrl("http://maps.googleapis.com/maps/api/staticmap?zoom=15&size=500x180&markers=size:big|color:red|"+lat+","+lon+"", imageLoader);
//        String address = getMyLocationAddress(Double.valueOf(lon),Double.valueOf(lat));
        holder.street.setText(address);
        holder.data.setText(data);
        return convertView;
    }

    private static class ViewHolder {
        TextView street;
        TextView data;
        NetworkImageView image;
    }


    private String secondsToString(int pTime) {
        return String.format("%02d:%02d", pTime / 60, pTime % 60);
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