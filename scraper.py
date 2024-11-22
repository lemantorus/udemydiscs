import requests
import psycopg2
from datetime import datetime,timedelta
perPage = 1000
class db:
    def __init__(self):
        self.db_config = {
            'dbname': 'udemydisc',
            'user': 'coder',
            'password': '09050165',
            'host': 'localhost',
            'port': '5432'
        }
        self.conn = None
        self.curr = None

    def connect(self):
        self.conn = psycopg2.connect(**self.db_config)
        self.curr = self.conn.cursor()

    def close(self):
        if self.conn:
            self.conn.close()

    def insert_coupon(self, coupon_data):
        insert_query = """
        INSERT INTO coupons (
            category, date, featured, image, language, lectures, name, price, rating,
            sale_end, sale_price, sale_start, short_description, slug, store, students,
            subcategory, subcatslug, type, url
        ) VALUES (
            %(category)s, %(date)s, %(featured)s, %(image)s, %(language)s, %(lectures)s, %(name)s, %(price)s, %(rating)s,
            %(sale_end)s, %(sale_price)s, %(sale_start)s, %(short_description)s, %(slug)s, %(store)s, %(students)s,
            %(subcategory)s, %(subcatslug)s, %(type)s, %(url)s
        )
        """
        self.curr.execute(insert_query, coupon_data)
        self.conn.commit()

def dateconvert(date):
    if date is None:
        return None
    try:
        dt = datetime.strptime(date, "%a, %d %b %Y %H:%M:%S %Z")
    except ValueError:
        dt = datetime.fromisoformat(date)
    return dt

def getCoupons():
    url = f'https://www.real.discount/api-web/all-courses/?store=Udemy&page=1&per_page={perPage}&orderby=undefined&free=0&search=&language=&cat='
    r = requests.get(url).json()
    return r['results']

def dataStruct(coupons):
    couponsList = []
    now = (datetime.now()-timedelta(days=1)).timestamp()
    for coupon in coupons:
        if coupon['type'] != 'Affiliate':
            sale_end = dateconvert(coupon.get('sale_end', None))
            if sale_end and sale_end.timestamp() > now:
                url = coupon.get('url', None)
                if 'click.linksynergy.com' in url:
                    url = url.split('RD_PARM1=')
                    if 'www.udemy.com' not in url[-1]:
                        url = 'https://www.udemy.com'+url[-1]
                    else:
                        url = url[-1]
                    

                coupon_data = {
                    'category': coupon.get('category', None),
                    'date': dateconvert(coupon.get('date', None)),
                    'featured': bool(coupon.get('featured', 0)),
                    'image': coupon.get('image', None),
                    'language': coupon.get('language', None),
                    'lectures': int(float(coupon.get('lectures', 0))),
                    'name': coupon.get('name', None),
                    'price': float(coupon.get('price', 0)),
                    'rating': float(coupon.get('rating', 0)),
                    'sale_end': sale_end,
                    'sale_price': float(coupon.get('sale_price', 0)),
                    'sale_start': dateconvert(coupon.get('sale_start', None)),
                    'short_description': coupon.get('shoer_description', None),
                    'slug': coupon.get('slug', None),
                    'store': coupon.get('store', None),
                    'students': coupon.get('students', 0),
                    'subcategory': coupon.get('subcategory', None),
                    'subcatslug': coupon.get('subcatslug', None),
                    'type': coupon.get('type', None),
                    'url': url,
                }
                couponsList.append(coupon_data)
    return couponsList

da = db()
da.connect()

coupons = getCoupons()
coupons = dataStruct(coupons=coupons)

for coupon in coupons:
    da.insert_coupon(coupon)

da.close()