USE [RanShop]
GO

/****** Object:  Table [dbo].[ShopItemMap]    Script Date: 12/21/2017 21:51:31 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

SET ANSI_PADDING ON
GO

CREATE TABLE [dbo].[ShopItemMap](
	[ProductNum] [int] IDENTITY(1,1) NOT NULL,
	[ItemMain] [int] NULL,
	[ItemSub] [int] NULL,
	[ItemName] [varchar](100) NULL,
	[ItemSec] [int] NULL,
	[ItemPrice] [varchar](100) NULL,
	[Itemstock] [varchar](100) NULL,
	[Category] [int] NULL,
	[PremiumItem] [int] NULL,
	[ItemImage] [varchar](300) NULL,
	[ItemStatus] [int] NULL,
	[date] [datetime] NOT NULL,
 CONSTRAINT [PK_ShopItemMap] PRIMARY KEY CLUSTERED 
(
	[ProductNum] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO

ALTER TABLE [dbo].[ShopItemMap] ADD  CONSTRAINT [DF_ShopItemMap_Category]  DEFAULT ((0)) FOR [Category]
GO

ALTER TABLE [dbo].[ShopItemMap] ADD  CONSTRAINT [DF_ShopItemMap_PremiumItem]  DEFAULT ((0)) FOR [PremiumItem]
GO

ALTER TABLE [dbo].[ShopItemMap] ADD  CONSTRAINT [DF_ShopItemMap_ItemStatus]  DEFAULT ((0)) FOR [ItemStatus]
GO

ALTER TABLE [dbo].[ShopItemMap] ADD  CONSTRAINT [DF_ShopItemMap_date]  DEFAULT (getdate()) FOR [date]
GO

